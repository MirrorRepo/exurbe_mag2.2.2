<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\ImageGallery\Model\Config\Source;

/**
 * Class Background
 * @package Magenest\ImageGallery\Model\Config\Source
 */
class Background implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Dark')]
            ,['value' => 1, 'label' => __('Light')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [0 => __('Dark'), 1 => __('Light')];
    }
}
