<?php
/**
 * wallee Magento 2
 *
 * This Magento 2 extension enables to process payments with wallee (https://www.wallee.com/).
 *
 * @package Wallee_Payment
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
namespace Wallee\Payment\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Wallee\Sdk\Service\TransactionService;

/**
 * Backend controller action to download a packing slip.
 */
class DownloadPackingSlip extends \Wallee\Payment\Controller\Adminhtml\Order
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::shipment';

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if ($orderId) {
            $transaction = $this->_transactionInfoRepository->getByOrderId($orderId);
            $document = $this->_apiClient->getService(TransactionService::class)->getPackingSlip(
                $transaction->getSpaceId(), $transaction->getTransactionId());
            return $this->_fileFactory->create($document->getTitle() . '.pdf', \base64_decode($document->getData()),
                DirectoryList::VAR_DIR, 'application/pdf');
        } else {
            return $this->_resultForwardFactory->create()->forward('noroute');
        }
    }

    public function packingslip_wallee($orderId_arr)
    {
      $pdfObj = array();
      foreach ($orderId_arr as $key => $orderId) {

        if ($orderId) {
            $transaction = $this->_transactionInfoRepository->getByOrderId($orderId);
            $document = $this->_apiClient->getService(TransactionService::class)->getPackingSlip(
                $transaction->getSpaceId(), $transaction->getTransactionId());
              $pdfObj[]=\base64_decode($document->getData());
          /*  return $this->_fileFactory->create($document->getTitle() . '.pdf', \base64_decode($document->getData()),
                DirectoryList::VAR_DIR, 'application/pdf');*/
        } else {
            return $this->_resultForwardFactory->create()->forward('noroute');
        }
      }
      return $pdfObj;
    /*  foreach($pdfObj as $pdfData){
          $this->_fileFactory->create(time(). '.pdf', \base64_decode($pdfData),
              DirectoryList::VAR_DIR, 'application/pdf');
      }*/

    }
}
