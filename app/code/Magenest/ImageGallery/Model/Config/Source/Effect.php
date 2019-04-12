<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\ImageGallery\Model\Config\Source;

class Effect implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('None')]
            , ['value' => 1, 'label' => __('Fade')]
            ,['value' => 2, 'label' => __('Elastic')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [0 => __('None'), 1 => __('Fade'),2 => __('Elastic')];
    }
}
