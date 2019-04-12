<?php

namespace Magenest\ImageGallery\Block\Widget;

/**
 * Class ImageGallery
 * @package Magenest\ImageGallery\Block\Widget
 */
class ImageGallery extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('landingspage.phtml');
    }
}
