<?php

namespace Magenest\ImageGallery\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Landingspage
 * @package Magenest\ImageGallery\Block
 */
class Landingspage extends Template
{
    /**
     * @var \Magenest\ImageGallery\Model\GalleryFactory
     */
    protected $_galleryCollectionFactory;

    /**
     * Landingspage constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Magenest\ImageGallery\Model\GalleryFactory $galleryCollectionFactory
     */
    public function __construct(
        Template\Context $context,
        \Magenest\ImageGallery\Model\GalleryFactory $galleryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_galleryCollectionFactory =$galleryCollectionFactory;
    }

    /**
     * @return string
     */
    public function getLandingsUrl()
    {
        return $this->getUrl('imagegallery');
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getUrl('imagegallery/index/gallery');
    }

    /**
     * load collection Image
     *
     * @return mixed
     */
    public function getLoadImageCollection()
    {
        $imageCollection = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\ImageGallery\Model\Image');

        return $imageCollection;
    }

    /**
     * load collection all gallery
     *
     * @return mixed
     */
    public function getLoadAllGalleryCollection()
    {
        $collection=$this->_galleryCollectionFactory->create()->getCollection();

        return $collection;
    }

    /**
     * load collection gallery for gallery page
     *
     * @return mixed
     */
    public function getLoadGalleryCollection()
    {
        $galleryCollection = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\ImageGallery\Model\Gallery');

        return $galleryCollection;
    }

    /**
     * load vertical dimension in store config to set style for image
     *
     * @return mixed
     */
    public function getVerticalConfig()
    {
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $ver =  $model->getValue('imagegallery/gallerypage/verticaldimension', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $ver;
    }

    /**
     * load horizontal dimension in store config to set style for image
     *
     * @return mixed
     */
    public function getHorizontalConfig()
    {
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $hor =  $model->getValue('imagegallery/gallerypage/horizontaldimension', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $hor;
    }

    /**
     * get gallery_id from url and return image_id of gallery
     *
     * @return array
     */
    public function getImageId()
    {
        $id = $this->getRequest()->getParams();
        $galleryId = $id['id'];  // get id of gallery;
        $galleryCollection=$this->getLoadGalleryCollection();
        $gallery = $galleryCollection->load($galleryId);
        $str = $gallery->getImageId();
        $imageIdAll = explode(',', $str);
        $imageId=[];

        foreach ($imageIdAll as $image) {
            if ($this->getLoadImageCollection()->load($image)->getStatus()==0) { //just show what images are enable
                array_push($imageId, $image);
            }
        }

        return $imageId;
    }
}
