<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2016-04-17T13:03:38+00:00
 * File:          app/code/Xtento/OrderExport/Observer/CustomerSaveAfterRegistrationObserver.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Observer;

use Xtento\OrderExport\Model\Export;

class CustomerSaveAfterRegistrationObserver extends AbstractEventObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // Check if customer is new, only export then
        if ($observer->getCustomer()->isObjectNew()) {
            $this->handleEvent($observer, self::EVENT_CUSTOMER_AFTER_REGISTRATION, Export::ENTITY_CUSTOMER);
        }
    }
}
