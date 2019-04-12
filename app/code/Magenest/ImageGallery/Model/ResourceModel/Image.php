<?php

namespace Magenest\ImageGallery\Model\ResourceModel;

/**
 * Class Image
 * @package Magenest\ImageGallery\Model\ResourceModel
 */
class Image extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
	 
    protected function _construct()
    {
        $this->_init('magenest_image_gallery_image', 'image_id');
    }
	
	
}
