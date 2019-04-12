<?php
/**
 * Copyright � Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Sales emails sending observer.
 *
 * Performs handling of cron jobs related to sending emails to customers
 * after creation/modification of Order, Invoice, Shipment or Creditmemo.
 */
class PdfinvoiceObserver implements ObserverInterface
{

  /**
    * @var ObjectManagerInterface
    */
   protected $_objectManager;

   /**
    * @param \Magento\Framework\ObjectManagerInterface $objectManager
    */
   public function __construct(
       \Magento\Framework\ObjectManagerInterface $objectManager
   ) {
       $this->_objectManager = $objectManager;
   }

    /**
     * Handles asynchronous email sending during corresponding
     * cron job.
     *
     * Also method is used in the next events:
     *
     * - config_data_sales_email_general_async_sending_disabled
     *
     * Works only if asynchronous email sending is enabled
     * in global settings.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      echo "rishi";
      $displayText = $observer->getData('mp_text');
		echo $displayText->getText() . " - Event </br>";
		$displayText->setText('Execute event successfully.');

		return $this;
    }
}
