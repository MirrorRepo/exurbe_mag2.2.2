<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\ImageGallery\Controller\Adminhtml\Gallery;

use Magenest\ImageGallery\Controller\Adminhtml\Gallery as GalleryController;

/**
 * Class MassDelete
 * @package Magenest\ImageGallery\Controller\Adminhtml\Gallery
 */
class MassDelete extends GalleryController
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $collection = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\ImageGallery\Model\Gallery');
        if (isset($collection['selected'])) {
            $cols = $collection['selected'];
        } else {
            foreach ($model->getCollection() as $item) {
                $model->load($item->getGalleryId())->delete();
            }
            $this->messageManager->addSuccessMessage(__('All records have been deteled.'));
            return $resultRedirect->setPath('*/*/');
        }
        $totals = 0;

        try {
            foreach ($cols as $item) {
                /** @var \Magenest\ImageGallery\Model\Gallery $item */
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
