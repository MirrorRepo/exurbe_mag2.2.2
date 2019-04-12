<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Controller\Adminhtml\Order;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Wallee\Payment\Controller\Adminhtml\Order\DownloadInvoice;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Pdfinvoices extends \Magento\Sales\Controller\Adminhtml\Order\PdfDocumentsMassAction
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Sales::invoice';

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Invoice
     */
    protected $pdfInvoice;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Invoice $pdfInvoice
     */

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Invoice $pdfInvoice
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->pdfInvoice = $pdfInvoice;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
    }

    /**
     * Print invoices for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    protected function massAction(AbstractCollection $collection)
    {
        //$invoicesCollection = $this->collectionFactory->create()->setOrderFilter(['in' => $collection->getAllIds()]);
              $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
              $pdf_array_wallee= array();
              $pdf_array_free= array();
        $obj = $this->_objectManager->create('Wallee\Payment\Controller\Adminhtml\Order\DownloadInvoice');
        $orderId_arr = $this->getRequest()->getParam('selected');
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
       $pdfreturn = $obj->invoice_wallee($pdf_array_wallee);

         $invoicesCollection = $this->collectionFactory->create()->addFieldToFilter('order_id', ['in' => $pdf_array_free]);
         $pdf_all_free=$this->pdfInvoice->getPdf($invoicesCollection->getItems())->render();
           $name_free = 'fileinvoice_free'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
            file_put_contents("/var/www/webroot/ROOT/var/".$name_free,$pdf_all_free);
        $pdf_tmp = new \Zend_Pdf();
        $pdf_merged = new \Zend_Pdf();
        $pdf_name=array();
        $i=1;


        foreach ($pdfreturn as $key => $value) {

          $name = 'fileinvoice'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
          $pdf_name[]=$name;
          file_put_contents("/var/www/webroot/ROOT/var/".$name,$value);
        }
        $pdf_name[]=$name_free;

            foreach ($pdf_name as $key => $value) {
               $pdfObj = $pdf_tmp->load("/var/www/webroot/ROOT/var/".$value);
               foreach($pdfObj->pages as $page){
                 $clonedPage = clone $page;
                  $pdf_merged->pages[] = $clonedPage;
               }
               unlink("/var/www/webroot/ROOT/var/".$value);
            }

              return $this->fileFactory->create(
                sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
              $pdf_merged->render(),
                DirectoryList::VAR_DIR,
                'application/pdf'
            );


        // if (!$invoicesCollection->getSize()) {
        //     $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
        //     return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        // }
        // return $this->fileFactory->create(
        //     sprintf('invoice%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
        //     $this->pdfInvoice->getPdf($invoicesCollection->getItems())->render(),
        //     DirectoryList::VAR_DIR,
        //     'application/pdf'
        // );
    }
}
