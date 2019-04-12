<?php

/**
 * Product:       Xtento_XtCore
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2018-03-20T10:40:20+00:00
 * File:          app/code/Xtento/XtCore/Setup/InstallData.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Setup;

use Magento\Framework\Exception\SessionException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Config Value Factory
     *
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    private $configValueFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * InstallData constructor.
     *
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\App\State $appState
    )
    {
        $this->configValueFactory = $configValueFactory;
        $this->appState = $appState;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        try {
            $this->doInstall();
        } catch (SessionException $e) {
            $this->appState->setAreaCode('adminhtml');
            $this->doInstall();
        }
    }

    protected function doInstall() {
        /** @var $configValue \Magento\Framework\App\Config\ValueInterface */
        $configValue = $this->configValueFactory->create();
        $configValue->load('xtcore/adminnotification/installation_date', 'path');
        $configValue->setValue(time())->setPath('xtcore/adminnotification/installation_date')->save();
    }
}
