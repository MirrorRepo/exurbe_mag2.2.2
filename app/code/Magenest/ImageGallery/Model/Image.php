<?php

namespace Magenest\ImageGallery\Model;
use Magenest\ImageGallery\Api\Data\ImageInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class Image
 * @package Magenest\ImageGallery\Model
 * @method int getImageId()
 * @method string getImage()
 * @method string getTitle()
 * @method string getDescription()
 * @method string getStatus()
 * @method int getSortorder()
 */
class Image extends \Magento\Framework\Model\AbstractModel //implements ImageInterface,IdentityInterface
{
	/**
     * CMS page cache tag
     */
    const CACHE_TAG = 'image';

  
    protected $_cacheTag = 'image';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'image';
    protected function _construct()
    {
        $this->_init('Magenest\ImageGallery\Model\ResourceModel\Image');
    }
}
