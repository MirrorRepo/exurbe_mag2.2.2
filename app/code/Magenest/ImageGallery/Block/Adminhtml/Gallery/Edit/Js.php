<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit;

use Magento\Backend\Block\Template\Context;

/**
 * Class Js
 * @package Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit
 */
class Js extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magenest\ImageGallery\Model\Gallery
     */
    protected $gallery;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Js constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magenest\ImageGallery\Model\Gallery $gallery
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magenest\ImageGallery\Model\GalleryFactory $gallery,
        array $data
    ) {
        $this->gallery = $gallery;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getDataGallery()
    {
        $data = $this->_request->getParams();
        if ($data) {
          $model = $this->gallery->create();
          //  $model = $this->gallery->create()->load($data['id']);
        } else {
            $model = $this->gallery->create();
        }

        return $model;
    }

    /**
     * @return int
     */
    public function getAssignType()
    {
        $data = $this->_request->getParams();
        $id = 0 ;
        if (isset($data['id'])) {
            $model = $this->getDataGallery();
            $id = $model->getAssignType();
        }

        return $id;
    }

    /**
     * @return string
     */
    public function getApplyImage()
    {
            $applyImage = $this->getDataGallery()->getImageId();
            $result = json_encode(explode(",", $applyImage));

        return $result;
    }

    /**
     * @return bool
     */
    public function checkEdit()
    {
        $data = $this->_request->getParams();
        if (isset($data['id'])) {
            return 1;
        }

        return 2;
    }
}
