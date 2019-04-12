<?php

namespace Magenest\ImageGallery\Block\Adminhtml;

/**
 * Class Image
 * @package Magenest\ImageGallery\Block\Adminhtml
 */
class Image extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Magenest_ImageGallery';
        $this->_controller = 'adminhtml_image';
        parent::_construct();
    }
}
