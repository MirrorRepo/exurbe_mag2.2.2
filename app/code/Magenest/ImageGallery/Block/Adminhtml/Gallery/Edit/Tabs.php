<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit;

/**
 * Class Tabs
 * @package Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('page_base_fieldset');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Gallery Information'));
    }

    /**
     *  render layout
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'general',
            ['label' => __('Gallery'),
                'content' => $this->getLayout()->createBlock(
                    'Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit\Tab\Main',
                    'imagegalley.gallery.tab.general'
                )->toHtml(),
            ]
        );
        $this->addTab(
            'galleryimages',
            ['label' => __('Gallery Images'),
                'content' => $this->getLayout()->createBlock(
                    'Magenest\ImageGallery\Block\Adminhtml\Gallery\Edit\Tab\GalleryImages',
                    'imagegalley.gallery.tab.galleryimages'
                )->toHtml(),
            ]
        );
    }
}
