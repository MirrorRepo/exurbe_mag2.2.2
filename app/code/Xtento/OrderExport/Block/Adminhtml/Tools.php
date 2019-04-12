<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2015-09-10T15:24:10+00:00
 * File:          app/code/Xtento/OrderExport/Block/Adminhtml/Tools.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Block\Adminhtml;

class Tools extends \Magento\Backend\Block\Template
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Xtento_OrderExport::tools.phtml');
    }
}
