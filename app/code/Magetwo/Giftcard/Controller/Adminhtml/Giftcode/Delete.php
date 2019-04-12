<?php
/**
 * A Magento 2 module named Magetwo/Giftcard
 * Copyright (C) 2017  2017
 * 
 * This file included in Magetwo/Giftcard is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Magetwo\Giftcard\Controller\Adminhtml\Giftcode;

class Delete extends \Magetwo\Giftcard\Controller\Adminhtml\Giftcode
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('giftcode_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Magetwo\Giftcard\Model\Giftcode');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Giftcode.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['giftcode_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Giftcode to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
