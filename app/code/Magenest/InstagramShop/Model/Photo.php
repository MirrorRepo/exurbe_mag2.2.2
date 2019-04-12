<?php

namespace Magenest\InstagramShop\Model;

/**
 * Class Photo
 * @package Magenest\InstagramShop\Model
 */
class Photo extends \Magento\Framework\Model\AbstractModel
{
    protected $_photoId = 'photo_id';

    protected $_url = 'url';

    protected $_source = 'source';

    protected $_caption = 'caption';

    protected $_productId = 'product_id';

    protected $_likes = 'likes';

    protected $_comments = 'comments';

    const SHOW_IN_WIDGET = 'show_in_widget';

    const CREATED_AT = 'created_at';

    /**
     * @return mixed
     */
    public function getPhotoId()
    {
        return $this->getData($this->_photoId);
    }

    /**
     * @param mixed $photoId
     * @return $this
     */
    public function setPhotoId($photoId)
    {
        $this->setData($this->_photoId, $photoId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->getData($this->_url);
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->setData($this->_url, $url);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getData($this->_source);
    }

    /**
     * @param mixed $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->setData($this->_source, $source);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->getData($this->_caption);
    }

    /**
     * @param mixed $caption
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->setData($this->_caption, $caption);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductIds()
    {
        return $this->getData($this->_productId);
    }

    /**
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->setData($this->_productId, $productId);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->getData($this->_likes);
    }

    /**
     * @param mixed $likes
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->setData($this->_likes, $likes);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->getData($this->_comments);
    }

    /**
     * @param mixed $comments
     * @return $this
     */
    public function setComments($comments)
    {
        $this->setData($this->_comments, $comments);
        return $this;
    }

    /**
     * @param mixed $visibility
     * @return $this
     */
    public function setShowInWidget($visibility)
    {
        $this->setData(self::SHOW_IN_WIDGET, $visibility);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShowedInWidget()
    {
        return $this->getData(self::SHOW_IN_WIDGET);
    }

    /**
     * @param $time
     * @return $this
     */
    public function setCreatedAt($time)
    {
        $this->setData(self::CREATED_AT, $time);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    protected function _construct()
    {
        $this->_init('Magenest\InstagramShop\Model\ResourceModel\Photo');
    }
}
