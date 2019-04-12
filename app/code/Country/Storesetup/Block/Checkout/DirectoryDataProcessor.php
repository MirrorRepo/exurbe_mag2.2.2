<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Country\Storesetup\Block\Checkout;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Store\Api\StoreResolverInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Directory data processor.
 *
 * This class adds various country and region dictionaries to checkout page.
 * This data can be used by other UI components during checkout flow.
 */
class DirectoryDataProcessor extends \Magento\Checkout\Block\Checkout\DirectoryDataProcessor
{
    /**
     * @var array
     */
    private $countryOptions;

    /**
     * @var array
     */
    private $regionOptions;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    private $regionCollectionFactory;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    private $countryCollectionFactory;

    /**
     * @var StoreResolverInterface
     */
    private $storeResolver;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollection
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollection
     * @param StoreResolverInterface $storeResolver
     * @param DirectoryHelper $directoryHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollection,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollection,
        StoreResolverInterface $storeResolver,
        DirectoryHelper $directoryHelper,
        StoreManagerInterface $storeManager,\Magento\Directory\Model\CountryFactory $countryFactory
    ) {

        parent::__construct($countryCollection, $regionCollection, $storeResolver, $directoryHelper);
        $this->countryCollectionFactory = $countryCollection;
        $this->regionCollectionFactory = $regionCollection;
        $this->storeResolver = $storeResolver;
        $this->directoryHelper = $directoryHelper;
        $this->storeManager = $storeManager;
        $this->countryFactory = $countryFactory;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries'] = [
                'country_id' => $this->getCountryOptions(),
                'region_id' => $this->getRegionOptions(),
            ];
        }
        return $jsLayout;
    }

    /**
     * Get country options list.
     *
     * @return array
     */
    private function getCountryOptions()
    {
        $allowCountryCode= array();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();



        if (!isset($this->countryOptions)) {
            $countryArray =  explode(',', $this->storeManager->getStore()->getConfig('general/country/allow'));
            foreach( $countryArray as $key=>$countryCode){
                $countryName = $objectManager->create('\Magento\Directory\Model\Country')->load($countryCode)->getName();
                $allowCountryCode[$key]['value'] =$countryCode;
                $allowCountryCode[$key]['label']  = $countryName;
                $allowCountryCode[$key]['is_region_visible'] = '';
            }

            $this->countryOptions = $allowCountryCode;
           // $this->countryOptions = $this->countryCollectionFactory->create()->loadByStore(4)->toOptionArray();
            $this->countryOptions = $this->orderCountryOptions($this->countryOptions);
        }


        return $this->countryOptions;
    }

    /**
     * Get region options list.
     *
     * @return array
     */
    private function getRegionOptions()
    {
       
        if (!isset($this->regionOptions)) {
            $this->regionOptions = $this->regionCollectionFactory->create()->addAllowedCountriesFilter(
                $this->storeManager->getStore()->getId()
            )->toOptionArray();
        }

        if($this->storeManager->getStore()->getId() == 3 || $this->storeManager->getStore()->getId() == 2 || $this->storeManager->getStore()->getId() == 4 || $this->storeManager->getStore()->getId() == 5 || $this->storeManager->getStore()->getId() == 6 )
             return array();
        else
            return $this->regionOptions;
        // return array();
    }

    /**
     * Sort country options by top country codes.
     *
     * @param array $countryOptions
     * @return array
     */
    private function orderCountryOptions(array $countryOptions)
    {
        $topCountryCodes = $this->directoryHelper->getTopCountryCodes();
        if (empty($topCountryCodes)) {
            return $countryOptions;
        }

        $headOptions = [];
        $tailOptions = [[
            'value' => 'delimiter',
            'label' => '──────────',
            'disabled' => true,
        ]];
        foreach ($countryOptions as $countryOption) {
            if (empty($countryOption['value']) || in_array($countryOption['value'], $topCountryCodes)) {
                array_push($headOptions, $countryOption);
            } else {
                array_push($tailOptions, $countryOption);
            }
        }
        return array_merge($headOptions, $tailOptions);
    }
}
