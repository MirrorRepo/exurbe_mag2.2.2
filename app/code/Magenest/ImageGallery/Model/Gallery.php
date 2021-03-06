<?php

namespace Magenest\ImageGallery\Model;

/**
 * Class Gallery
 * @package Magenest\ImageGallery\Model
 * @method int getGalleryId()
 * @method string getImageId()
 * @method string getTitle()
 * @method int getThumbnailId()
 * @method string getStatus()
 */
class Gallery extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Gallery constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init('Magenest\ImageGallery\Model\ResourceModel\Gallery');
    }
}
