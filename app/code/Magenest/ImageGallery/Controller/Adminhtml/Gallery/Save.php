<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\ImageGallery\Controller\Adminhtml\Gallery;

use \Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Magenest\ImageGallery\Controller\Adminhtml\Gallery
 */
class Save extends \Magento\Backend\App\Action
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
     * Save constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        UploaderFactory $fileUploaderFactory
    ) {

        // $this->_request = $request;
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
        $post = $this->getRequest()->getPostValue();
        echo "<pre>";
        print_r($post);
        echo "</pre>";
        \Magento\Framework\App\ObjectManager::getInstance()->create('Psr\Log\LoggerInterface')->debug(print_r($post, true));
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $array=[
                'image_id'=>isset($post['image_id']) ? $post['image_id'] : null ,
                'title'=>isset($post['titleGallery']) ? $post['titleGallery'] : null ,
                'thumbnail_id'=>isset($post['thumbnail_id'])? $post['thumbnail_id'] : null ,
                'status'=>isset($post['status']) ? $post['status'] : null
            ];

            $model = $this->_objectManager->create('Magenest\ImageGallery\Model\Gallery');
            if (isset($post['gallery_id'])) {
                $model->load($post['gallery_id']);
            }
            $model->addData($array);

            $model->save();
            $this->messageManager->addSuccess(__('The rule has been saved.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
               // die("00");
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while saving the rule.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($post);
            return $resultRedirect->setPath('imagegallery/gallery/edit', ['id'=>$this->getRequest()->getParam('id')]);
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
