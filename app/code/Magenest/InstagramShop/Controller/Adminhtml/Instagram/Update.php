<?php

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Cron;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Class Update
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class Update extends Action
{
    /**
     * @var Cron
     */
    protected $_updatePhoto;

    /**
     * @param Context $context
     * @param Cron $updatePhoto
     */
    public function __construct(
        Context $context,
        Cron $updatePhoto
    )
    {
        $this->_updatePhoto = $updatePhoto;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $this->_updatePhoto->getTaggedPhotos();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $this->_redirect('instagram/tag');
    }
}
