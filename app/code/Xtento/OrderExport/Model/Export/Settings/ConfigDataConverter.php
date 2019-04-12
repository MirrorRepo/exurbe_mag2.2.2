<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2017-11-27T20:04:32+00:00
 * File:          app/code/Xtento/OrderExport/Model/Export/Settings/ConfigDataConverter.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Model\Export\Settings;

class ConfigDataConverter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($source)
    {
        $settings = [];
        foreach ($source->getElementsByTagName('setting') as $setting) {
            $name = $setting->getAttribute('name');
            $settings[$name] = $setting->nodeValue;
        }
        return $settings;
    }
}
