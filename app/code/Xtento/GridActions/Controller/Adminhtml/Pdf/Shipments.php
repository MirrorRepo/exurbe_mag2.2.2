<?php

/**
 * Product:       Xtento_GridActions
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2017-03-22T19:16:25+00:00
 * File:          app/code/Xtento/GridActions/Controller/Adminhtml/Pdf/Shipments.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\GridActions\Controller\Adminhtml\Pdf;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Shipment;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory as ShipmentCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Xtento\GridActions\Ui\Component\MassAction\CustomFilter;
use Wallee\Payment\Controller\Adminhtml\Order\DownloadPackingSlip;
/**
 * Handling print actions
 *
 * @package Xtento\GridActions\Controller\Adminhtml\Pdf
 */
class Shipments extends \Magento\Sales\Controller\Adminhtml\Order\Pdfshipments
{
    /**
     * Shipments constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Shipment $shipment
     * @param ShipmentCollectionFactory $shipmentCollectionFactory
     * @param CustomFilter $customFilter
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Shipment $shipment,
        ShipmentCollectionFactory $shipmentCollectionFactory,
        CustomFilter $customFilter
    ) {
        parent::__construct($context, $customFilter, $collectionFactory, $dateTime, $fileFactory, $shipment, $shipmentCollectionFactory);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_GridActions::ship');
    }
}
