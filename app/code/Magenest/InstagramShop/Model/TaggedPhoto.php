<?php
namespace Magenest\InstagramShop\Model;

/**
 * Class TaggedPhoto
 * @package Magenest\InstagramShop\Model
 */
class TaggedPhoto extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\ResourceModel\TaggedPhoto');
    }
}
