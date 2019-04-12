<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Store and language switcher block
 */
namespace Favourite\FProduct\Block;

use Magento\Directory\Helper\Data;
use Magento\Store\Model\Group;

class Switcher extends \Magento\Framework\View\Element\Template
{
    /**
     * @var bool
     */
    protected $_storeInUrl;

    protected $_catalogSession;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * Constructs
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Customer\Model\Session $customerSession,
       // \Magento\Store\Model\StoreManagerInterface $storeManager,
       // \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        $this->_postDataHelper = $postDataHelper;
         $this->_customerSession = $customerSession;
      //  $this->_storeManager = $storeManager;
       // $this->_logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * @return int|null|string
     */
    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    public function getStoreData(){
    $storeManagerDataList = $this->_storeManager->getStores();
     $options = array();

     foreach ($storeManagerDataList as $key => $value) {

			$wesite_code	=	substr($value['name'],0,2);

            $options[] = ['label' => $value['name'],'code'=>$value['code'], 'value' => $key,'website_code'=>$wesite_code];
     }
	 foreach ($options as $option){
		 $newOptionArray[$option['website_code']][] = $option;
	}
	$options = $newOptionArray;
    return $options;
    }

    /**
     * @return int|null|string
     */
    public function getCurrentGroupId()
    {
        return $this->_storeManager->getStore()->getGroupId();
    }

    /**
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return array
     */
    public function getRawGroups()
    {
        if (!$this->hasData('raw_groups')) {
            $websiteGroups = $this->_storeManager->getWebsite()->getGroups();

            $groups = [];
            foreach ($websiteGroups as $group) {
                $groups[$group->getId()] = $group;
            }
            $this->setData('raw_groups', $groups);
        }
        return $this->getData('raw_groups');
    }

    /**
     * @return array
     */
    public function getRawStores()
    {
        if (!$this->hasData('raw_stores')) {
            $websiteStores = $this->_storeManager->getWebsite()->getStores();
            $stores = [];
            foreach ($websiteStores as $store) {
                /* @var $store \Magento\Store\Model\Store */
                if (!$store->isActive()) {
                    continue;
                }
                $localeCode = $this->_scopeConfig->getValue(
                    Data::XML_PATH_DEFAULT_LOCALE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $store
                );
                $store->setLocaleCode($localeCode);
                $params = ['_query' => []];
                if (!$this->isStoreInUrl()) {
                    $params['_query']['___store'] = $store->getCode();
                }
                $baseUrl = $store->getUrl('', $params);

                $store->setHomeUrl($baseUrl);
                $stores[$store->getGroupId()][$store->getId()] = $store;
            }
            $this->setData('raw_stores', $stores);
        }
        return $this->getData('raw_stores');
    }

    /**
     * Retrieve list of store groups with default urls set
     *
     * @return Group[]
     */
    public function getGroups()
    {
        if (!$this->hasData('groups')) {
            $rawGroups = $this->getRawGroups();
            $rawStores = $this->getRawStores();

            $groups = [];
            $localeCode = $this->_scopeConfig->getValue(
                Data::XML_PATH_DEFAULT_LOCALE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            foreach ($rawGroups as $group) {
                /* @var $group Group */
                if (!isset($rawStores[$group->getId()])) {
                    continue;
                }
                if ($group->getId() == $this->getCurrentGroupId()) {
                    $groups[] = $group;
                    continue;
                }

                $store = $group->getDefaultStoreByLocale($localeCode);

                if ($store) {
                    $group->setHomeUrl($store->getHomeUrl());
                    $groups[] = $group;
                }
            }
            $this->setData('groups', $groups);
        }
        return $this->getData('groups');
    }

    /**
     * @return \Magento\Store\Model\Store[]
     */
    public function getStores()
    {
        if (!$this->getData('stores')) {
            $rawStores = $this->getRawStores();

            $groupId = $this->getCurrentGroupId();
            if (!isset($rawStores[$groupId])) {
                $stores = [];
            } else {
                $stores = $rawStores[$groupId];
            }
            $this->setData('stores', $stores);
        }
        return $this->getData('stores');
    }

    /**
     * @return string
     */
    public function getCurrentStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * @return bool
     */
    public function isStoreInUrl()
    {
        if ($this->_storeInUrl === null) {
            $this->_storeInUrl = $this->_storeManager->getStore()->isUseStoreInUrl();
        }
        return $this->_storeInUrl;
    }

    /**
     * Get store code
     *
     * @return string
     */
    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * Get store name
     *
     * @return null|string
     */
    public function getStoreName()
    {
        return $this->_storeManager->getStore()->getName();
    }

    /**
     * Returns target store post data
     *
     * @param \Magento\Store\Model\Store $store
     * @param array $data
     * @return string
     */
    public function getTargetStorePostData(\Magento\Store\Model\Store $store, $data = [])
    {
        $data[\Magento\Store\Api\StoreResolverInterface::PARAM_NAME] = $store->getCode();
        return $this->_postDataHelper->getPostData(
            $this->getUrl('stores/store/switch'),
            $data
        );
    }

        public function getTargetStorePostData1($code)
    {
        $data[\Magento\Store\Api\StoreResolverInterface::PARAM_NAME] = $code;
        return $this->_postDataHelper->getPostData(
            $this->getUrl('fproduct/store/switch'),
            $data
        );
    }
    public function get_base_url($code,$sess="")
    {

        //$this->getCatalogSession()->setValue($sess);
        if(strpos($this->_storeManager->getStore()->getCurrentUrl(false),"stcode"))
        {
          //die("xxx");
          $stcode="?stcode=".$sess;
          $url1=explode("?stcode=",$this->_storeManager->getStore()->getCurrentUrl(false));
        //  print_r($url1);die("ccccc");
          $url=$url1[0].$stcode;
          //$url=$url1[0];
          //echo $url[];
        }else {
          //die("xxx2");
          $url=$this->_storeManager->getStore()->getCurrentUrl(false)."?stcode=".$sess;
         // $url=$this->_storeManager->getStore()->getBaseUrl()."?stcode=".$sess;
        }

       return str_replace($this->getStoreCode(),$code,$url);
        // return $this->getBaseUrl();
    }

        public function getCatalogSession()
    {
        return $this->_customerSession;
    }


    public function deb($layout)
    {
        $i=3;
        echo "<pre>";
        foreach ($layout as $key => $value) {

             if($i==3)
            {
                   print_r($value);
             }

           $i++;
        }
        echo "</pre>";
      exit;
    }
}
