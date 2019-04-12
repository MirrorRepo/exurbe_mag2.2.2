<?php

namespace Magenest\ImageGallery\Model\ResourceModel\Gallery;

/**
 * Gallery Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Initialize resource collection
     * *
    @return void
     */
    public function _construct()
    {
        $this->_init('Magenest\ImageGallery\Model\Gallery', 'Magenest\ImageGallery\Model\ResourceModel\Gallery');
    }
}
