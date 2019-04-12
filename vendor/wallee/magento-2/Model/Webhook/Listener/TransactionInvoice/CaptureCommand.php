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
namespace Wallee\Payment\Model\Webhook\Listener\TransactionInvoice;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Wallee\Sdk\Model\Transaction;
use Wallee\Sdk\Model\TransactionState;

/**
 * Webhook listener command to handle captured transaction invoices.
 */
class CaptureCommand extends AbstractCommand
{

    /**
     *
     * @param \Wallee\Sdk\Model\TransactionInvoice $entity
     * @param Order $order
     */
    public function execute($entity, Order $order)
    {
        $transaction = $entity->getCompletion()
            ->getLineItemVersion()
            ->getTransaction();
        $invoice = $this->getInvoiceForTransaction($transaction, $order);
        if (! ($invoice instanceof Invoice) || $invoice->getState() == Invoice::STATE_OPEN) {
            $isOrderInReview = ($order->getState() == Order::STATE_PAYMENT_REVIEW);

            if (! ($invoice instanceof Invoice)) {
                $order->setWalleeInvoiceAllowManipulation(true);
            }

            if (! ($invoice instanceof Invoice) || $invoice->getState() == Invoice::STATE_OPEN) {
                /** @var \Magento\Sales\Model\Order\Payment $payment */
                $payment = $order->getPayment();
                $payment->registerCaptureNotification($entity->getAmount());
                if (! ($invoice instanceof Invoice)) {
                    $invoice = $payment->getCreatedInvoice();
                }
                $invoice->setWalleeCapturePending(false);
                $order->addRelatedObject($invoice);
            }

            if ($transaction->getState() == TransactionState::COMPLETED) {
                $order->setStatus('processing_wallee');
            }

            if ($isOrderInReview) {
                $order->setState(Order::STATE_PAYMENT_REVIEW);
                $order->addStatusToHistory(true);
            }

            $this->_orderRepository->save($order);
            $this->sendOrderEmail($order);
        }
    }

    protected function createInvoice(Transaction $transaction, Order $order)
    {
        $invoice = $order->prepareInvoice();
        $invoice->register();
        $invoice->setTransactionId(
            $order->getWalleeSpaceId() . '_' . $order->getWalleeTransactionId());
        $order->addRelatedObject($invoice);
        return $invoice;
    }
}