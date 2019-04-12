<?php

/**
 * Product:       Xtento_GridActions
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2017-07-04T13:24:48+00:00
 * File:          app/code/Xtento/GridActions/Controller/Adminhtml/Pdf/Invoices.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Controller\Adminhtml\Pdf;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory;
use Xtento\GridActions\Ui\Component\MassAction\CustomFilter;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Wallee\Payment\Controller\Adminhtml\Order\DownloadInvoice;

/**
 * Handling print actions
 *
 * @package Xtento\GridActions\Controller\Adminhtml\Pdf
 */
class Invoices extends \Magento\Sales\Controller\Adminhtml\Order\Pdfinvoices
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Xtento\XtCore\Helper\Utils
     */
    protected $utilsHelper;

    /**
     * Invoices constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Invoice $pdfInvoice
     * @param CustomFilter $customFilter
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Invoice $pdfInvoice,
        CustomFilter $customFilter,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Xtento\XtCore\Helper\Utils $utilsHelper
    ) {
        parent::__construct($context, $customFilter, $collectionFactory, $dateTime, $fileFactory, $pdfInvoice);
        $this->productMetadata = $productMetadata;
        $this->utilsHelper = $utilsHelper;
    }

    /**
     * Print invoices for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    protected function massAction(AbstractCollection $collection)
    {
      $orderId_arr = $collection->getAllIds();
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $pdf_array_wallee= array();
      $pdf_array_free= array();
      //print_r($orderId_arr);
      foreach ($orderId_arr as $key => $value) {

        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId_arr[$key]);
        $payment = $order->getPayment();
        if($payment->getMethod()=="free")
        {
        $pdf_array_free[]=$value;

        }else{
        $pdf_array_wallee[$key]['order_id']=$value;
        $pdf_array_wallee[$key]['payment_method']= $payment->getMethod();
        }


      }
    //  print_r($pdf_array_wallee);
    //  die();
      // $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId_arr[0]);
      // $payment = $order->getPayment();
      // echo $payment->getMethod(); die();
       $obj = $this->_objectManager->create('Wallee\Payment\Controller\Adminhtml\Order\DownloadInvoice');
      $pdfreturn = $obj->invoice_wallee($pdf_array_wallee);
    //  print_r($pdfreturn);die();
      $pdf_tmp = new \Zend_Pdf();
      $pdf_merged = new \Zend_Pdf();
      $pdf_name=array();
      $name_free="";
      $i=1;
if(!empty($pdf_array_free)){
      if ($this->utilsHelper->mageVersionCompare($this->productMetadata->getVersion(), '2.1.3', '>=')) {
          // Starting from Magento 2.1.3, $collection returns order IDs
          $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('order_id', ['in' => $pdf_array_free]);
      } else {
          $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('entity_id', ['in' => $pdf_array_free]);
      }


       $pdf_all_free=$this->pdfInvoice->getPdf($invoicesCollection->getItems())->render();
         $name_free = 'fileinvoice_free'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
          file_put_contents("/var/www/webroot/ROOT/var/".$name_free,$pdf_all_free);
}
      foreach ($pdfreturn as $key => $value) {

        $name = 'fileinvoice'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
        $pdf_name[]=$name;
        file_put_contents("/var/www/webroot/ROOT/var/".$name,$value);
      }
      if(!empty($pdf_array_free)){
          $pdf_name[]=$name_free;
        }
          foreach ($pdf_name as $key => $value) {
             $pdfObj = $pdf_tmp->load("/var/www/webroot/ROOT/var/".$value);
             foreach($pdfObj->pages as $page){
               $clonedPage = clone $page;
                $pdf_merged->pages[] = $clonedPage;
             }
             unlink("/var/www/webroot/ROOT/var/".$value);
          }
     //
            return $this->fileFactory->create(
              sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $pdf_merged->render(),
              DirectoryList::VAR_DIR,
              'application/pdf'
          );
        // if ($this->utilsHelper->mageVersionCompare($this->productMetadata->getVersion(), '2.1.3', '>=')) {
        //     // Starting from Magento 2.1.3, $collection returns order IDs
        //     $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('order_id', ['in' => $collection->getAllIds()]);
        // } else {
        //     $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('entity_id', ['in' => $collection->getAllIds()]);
        // }
        // if (!$invoicesCollection->getSize()) {
        //     $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
        //     return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        // }
        //
        // return $this->fileFactory->create(
        //     sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
        //     $this->pdfInvoice->getPdf($invoicesCollection->getItems())->render(),
        //     DirectoryList::VAR_DIR,
        //     'application/pdf'
        // );
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_GridActions::invoice');
    }
}
