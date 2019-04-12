<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\ImageGallery\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Magenest\ImageGallery\Controller\Adminhtml\Image
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
     * @param RequestInterface $request
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
        $post = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!isset($post['image'])) {
            $post['image'] = [];
        }
        $image = $this->saveBackGround($_FILES['image'], $post['image']);
        if (is_array($post['image']) && !empty($post['image'])) {
            $post['image'] = $post['image']['value'];
        }
        if ($image == 'deleted' || $image == '') {
            $post['image'] = null;
        } else {
            $post['image'] = $image;
        }
        if (!$post) {
            return $resultRedirect->setPath('imagegallery/image/');
        }

        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);
            $array = [
                'title' => $post['title'],
                //'description' => $post['description'],
                'group_name' => $post['group_name'],
				//'sortorder' => $post['sortorder'],
                'status' => $post['status']

            ];
            $model = $this->_objectManager->create('Magenest\ImageGallery\Model\Image');
            if (isset($post['image_id'])) {
                $model->load($post['image_id']);
            }
            $model->addData($array);

            $model->setData($post);

            $model->save();
            $this->messageManager->addSuccess(__('The rule has been saved.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while saving the rule.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($post);
            return $resultRedirect->setPath('imagegallery/image/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
    }

    /**
     * @param $value
     * @param $post
     * @return string
     */
    public function saveBackGround($value, $post)
    {
        if (!empty($value['name']) || !empty($post)) {
            /** Deleted file */
            if (!empty($post['delete']) && !empty($post['value'])) {
                $path = $this->_filesystem->getDirectoryRead(
                    DirectoryList::MEDIA
                );
                if ($path->isFile($post['value'])) {
                    $this->_filesystem->getDirectoryWrite(
                        DirectoryList::MEDIA
                    )->delete($post['value']);
                }
                if (empty($value['name'])) {
                    return 'deleted';
                }
            }
            if (empty($value['name']) && !empty($post)) {
                return $post['value'];
            }
            $path = $this->_filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath(
                'imagegallery/template/'
            );
            try {
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(false);
                $result = $uploader->save($path);
                if (is_array($result) && !empty($result['name'])) {
                    return 'imagegallery/template/' . $result['name'];
                }
            } catch (\Exception $e) {
                if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                    $this->_logger->critical($e);
                }
                $this->_logger->critical($e);
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
