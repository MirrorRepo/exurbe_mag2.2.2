<?php

namespace Magenest\ImageGallery\Model\ResourceModel;

/**
 * Class Gallery
 * @package Magenest\ImageGallery\Model\ResourceModel
 */
class Gallery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_image_gallery_gallery', 'gallery_id');
    }
}
