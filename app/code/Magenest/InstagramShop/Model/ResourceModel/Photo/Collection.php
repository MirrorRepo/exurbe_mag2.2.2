<?php

namespace Magenest\InstagramShop\Model\ResourceModel\Photo;

/**
 * Class Collection
 * @package Magenest\InstagramShop\Model\ResourceModel\Photo
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\Photo', 'Magenest\InstagramShop\Model\ResourceModel\Photo');
    }
}
