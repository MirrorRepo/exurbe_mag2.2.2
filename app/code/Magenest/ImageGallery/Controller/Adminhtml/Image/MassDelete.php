<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\ImageGallery\Controller\Adminhtml\Image;

use Magenest\ImageGallery\Controller\Adminhtml\Image as ImageController;

/**
 * Class MassDelete
 * @package Magenest\ImageGallery\Controller\Adminhtml\Gallery
 */
class MassDelete extends ImageController
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $collection = $this->getRequest()->getParams();
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\ImageGallery\Model\Image');

        //not chose all
        if (isset($collection['selected'])) {
            $cols = $collection['selected'];
        } // chose all image
        else {
            $cols=$model->getCollection()->getAllIds();
        }

        //check image exist in gallery
        $galleryCollection=\Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\ImageGallery\Model\Gallery')->getCollection();
        $idValid=[];
        foreach ($cols as $idChose) {
            foreach ($galleryCollection as $gallery) {
                $str = $gallery->getImageId();
                $imageIdAll = explode(',', $str);
                foreach ($imageIdAll as $id) {
                    if ($idChose==$id) {
                        $this->messageManager->addErrorMessage(__('Image ID '.$idChose.' exist in gallery. Remove it in gallery before delete.'));
                    } else {
                        array_push($idValid, $idChose);
                    }
                }
            }
        }
        $totals = 0;
        try {
            foreach ($idValid as $item) {
                /** @var \Magenest\ImageGallery\Model\Image $item */
                $model ->load($item);
                $model->delete();
                $totals++;
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deteled.', $totals));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while delete the post(s).'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
