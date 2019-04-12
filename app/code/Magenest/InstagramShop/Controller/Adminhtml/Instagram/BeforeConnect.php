<?php

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Client;
use Magento\Backend\App\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;

class BeforeConnect extends Action
{
    protected $client;

    protected $_config;

    protected $_scopeConfig;

    public function __construct(
        Action\Context $context,
        Client $client,
        \Magento\Config\Model\ResourceModel\Config $config,
        ScopeConfigInterface $scopeConfig)
    {
        $this->_config = $config;
        $this->client = $client;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function execute()
    {
        $clientId = $this->getRequest()->getParam('client_id');
        $clientSecret = $this->getRequest()->getParam('client_secret');
        if ($clientId == "" || $clientSecret == "") {
            $this->messageManager->addErrorMessage(__("Please fill in ClientId and ClientSecret."));
            return $this->_redirect('admin/system_config/edit/section/magenest_instagram_shop');
        } else {
            $this->_config->saveConfig('magenest_instagram_shop/instagram/client_id', $clientId, 'default', 0);
            $this->_config->saveConfig('magenest_instagram_shop/instagram/client_secret', $clientSecret, 'default', 0);

            $this->refreshConfig();
            $this->refreshCache();
            $requestUrl = $this->client->createAuthUrl($clientId);
            return $this->_redirect($requestUrl);
        }
    }

    protected function refreshConfig()
    {
        $useFormKey = $this->_scopeConfig->getValue('admin/security/use_form_key', 'default', 0);
        $this->_config->saveConfig('admin/security/use_form_key', 0, 'default', 0);
        $this->_config->saveConfig('magenest_instagram_shop/instagram/change_secret_key', $useFormKey, 'default', 0);
    }

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
