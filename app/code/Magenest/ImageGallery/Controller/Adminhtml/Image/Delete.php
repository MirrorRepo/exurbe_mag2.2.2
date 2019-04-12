<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\ImageGallery\Controller\Adminhtml\Image;

use \Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Magenest\ImageGallery\Controller\Adminhtml\Image
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * Delete constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $fileUploaderFactory
    ) {
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct($context);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $post = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($post) {
            try {
                $model = $this->_objectManager->create('Magenest\ImageGallery\Model\Image');
                $model->load($post);
                $model->delete();
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the rule.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($post);
                return $resultRedirect->setPath(
                    'imagegallery/image/edit',
                    ['id'=>$this->getRequest()->getParam('id')]
                );
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
