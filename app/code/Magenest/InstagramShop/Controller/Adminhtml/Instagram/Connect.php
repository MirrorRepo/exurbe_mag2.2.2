<?php

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Client;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Connect
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class Connect extends Action
{
    /** @var \Magento\Config\Model\ResourceModel\Config */
    protected $_config;

    protected $client;

    protected $_scopeConfig;

    /**
     * Connect constructor.
     * @param Context $context
     * @param \Magento\Config\Model\ResourceModel\Config $config
     * @param Client $client
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        \Magento\Config\Model\ResourceModel\Config $config,
        Client $client,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->client = $client;
        $this->_config = $config;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        try {
            $this->connect();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $this->refreshConfig();
        $this->refreshCache();
        return $this->_redirect('adminhtml/system_config/edit/section/magenest_instagram_shop/');
    }

    protected function refreshConfig()
    {
        $this->_config->saveConfig('admin/security/use_form_key', 0, 'default', 0);
    }

    protected function connect()
    {
        $error = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');

        if (!(isset($error) || isset($code))) {
            return;
        }

        $client = $this->client;
        if ($code) {
            /** use code exchange for access token */
            $data = $client->fetchAccessToken($code);
            $data = json_decode($data, true);
            $token = $data['access_token'];
            $id = $data['user']['id'];

            /** save access token at store configuration */
            $this->_config->saveConfig('magenest_instagram_shop/instagram/access_token', $token, 'default', 0);
            $this->_config->saveConfig('magenest_instagram_shop/instagram/account_id', $id, 'default', 0);

            /** subscribe update from user */
            /** This endpoint has been retired */
//            $client->subscribe();
        }
    }

    /**
     * update config
     */
    protected function refreshCache()
    {
        $_cacheTypeList = $this->_objectManager->create(\Magento\Framework\App\Cache\TypeListInterface::class);
        $_cacheFrontendPool = $this->_objectManager->create(\Magento\Framework\App\Cache\Frontend\Pool::class);
        $types = ['config', 'full_page'];
        foreach ($types as $type) {
            $_cacheTypeList->cleanType($type);
        }
        foreach ($_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
