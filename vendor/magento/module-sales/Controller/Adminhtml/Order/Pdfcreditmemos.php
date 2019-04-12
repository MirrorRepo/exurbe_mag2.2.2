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
use Magento\Sales\Model\Order\Pdf\Creditmemo;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Wallee\Payment\Controller\Adminhtml\Order\DownloadRefund;

/**
 * Class Pdfcreditmemos
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Pdfcreditmemos extends \Magento\Sales\Controller\Adminhtml\Order\PdfDocumentsMassAction
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Sales::creditmemo';

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Creditmemo
     */
    protected $pdfCreditmemo;

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
     * @param Creditmemo $pdfCreditmemo
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Creditmemo $pdfCreditmemo
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->pdfCreditmemo = $pdfCreditmemo;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
    }

    /**
     * Print credit memos for selected orders
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    protected function massAction(AbstractCollection $collection)
    {

      $obj = $this->_objectManager->create('Wallee\Payment\Controller\Adminhtml\Order\DownloadRefund');
      $orderId_arr = $this->getRequest()->getParam('selected');

     $pdfreturn = $obj->creditmemo_wallee($orderId_arr);
      $pdf_tmp = new \Zend_Pdf();
      $pdf_merged = new \Zend_Pdf();
      $pdf_name=array();
      $i=1;


      foreach ($pdfreturn as $key => $value) {

        $name = 'filecreditmemo'.$key.$this->dateTime->date('Y-m-d_H-i-s'). '.pdf';
        $pdf_name[]=$name;
        file_put_contents("/var/www/webroot/ROOT/var/".$name,$value);
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
              sprintf('creditmemo%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
            $pdf_merged->render(),
              DirectoryList::VAR_DIR,
              'application/pdf'
          );


        // $creditmemoCollection = $this->collectionFactory->create()->setOrderFilter(['in' => $collection->getAllIds()]);
        // if (!$creditmemoCollection->getSize()) {
        //     $this->messageManager->addError(__('There are no printable documents related to selected orders.'));
        //     return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        // }
        // return $this->fileFactory->create(
        //     sprintf('creditmemo%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
        //     $this->pdfCreditmemo->getPdf($creditmemoCollection->getItems())->render(),
        //     DirectoryList::VAR_DIR,
        //     'application/pdf'
        // );
    }
}
