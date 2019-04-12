<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Controller\Adminhtml\Order;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Shipment;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Wallee\Payment\Controller\Adminhtml\Order\DownloadPackingSlip;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Pdfshipments extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Sales::ship';

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Shipment
     */
    protected $pdfShipment;

    /**
     * @var ShipmentCollectionFactory
     */
    protected $shipmentCollectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Shipment $shipment
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Shipment $shipment,
        ShipmentCollectionFactory $shipmentCollectionFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->pdfShipment = $shipment;
        $this->collectionFactory = $collectionFactory;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
        parent::__construct($context, $filter);
    }

    /**
     * Print shipments for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|\Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
      $obj = $this->_objectManager->create('Wallee\Payment\Controller\Adminhtml\Order\DownloadPackingSlip');
      $orderId_arr = $collection->getAllIds();//$this->getRequest()->getParam('selected');
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
        $pdf_array_wallee[]=$value;
        }
      }
  if(!empty($pdf_array_free)){
      $shipmentsCollection = $this->shipmentCollectionFactory
          ->create()
          ->setOrderFilter(['in' => $pdf_array_free]);
      if (!$shipmentsCollection->getSize()) {
          $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
        //  return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
      }
        $pdf_all_free= $this->pdfShipment->getPdf($shipmentsCollection->getItems())->render();
        $name_free = 'fileinvoice_free'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
         file_put_contents("/var/www/webroot/ROOT/var/".$name_free,$pdf_all_free);
}
      $pdfreturn = $obj->packingslip_wallee($pdf_array_wallee);
      $pdf_tmp = new \Zend_Pdf();
      $pdf_merged = new \Zend_Pdf();
      $pdf_name=array();
      foreach ($pdfreturn as $key => $value) {
        $name = 'filepackingslip'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
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

            return $this->fileFactory->create(
              sprintf('packingslip%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $pdf_merged->render(),
              DirectoryList::VAR_DIR,
              'application/pdf'
          );
        // $shipmentsCollection = $this->shipmentCollectionFactory
        //     ->create()
        //     ->setOrderFilter(['in' => $collection->getAllIds()]);
        // if (!$shipmentsCollection->getSize()) {
        //     $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
        //     return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        // }
        // return $this->fileFactory->create(
        //     sprintf('packingslip%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
        //     $this->pdfShipment->getPdf($shipmentsCollection->getItems())->render(),
        //     DirectoryList::VAR_DIR,
        //     'application/pdf'
        // );
    }
}
