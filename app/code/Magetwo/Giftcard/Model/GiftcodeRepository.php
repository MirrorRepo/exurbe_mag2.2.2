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

namespace Magetwo\Giftcard\Model;

use Magetwo\Giftcard\Api\GiftcodeRepositoryInterface;
use Magetwo\Giftcard\Api\Data\GiftcodeSearchResultsInterfaceFactory;
use Magetwo\Giftcard\Api\Data\GiftcodeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magetwo\Giftcard\Model\ResourceModel\Giftcode as ResourceGiftcode;
use Magetwo\Giftcard\Model\ResourceModel\Giftcode\CollectionFactory as GiftcodeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class GiftcodeRepository implements giftcodeRepositoryInterface
{

    protected $resource;

    protected $giftcodeFactory;

    protected $giftcodeCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataGiftcodeFactory;

    private $storeManager;


    /**
     * @param ResourceGiftcode $resource
     * @param GiftcodeFactory $giftcodeFactory
     * @param GiftcodeInterfaceFactory $dataGiftcodeFactory
     * @param GiftcodeCollectionFactory $giftcodeCollectionFactory
     * @param GiftcodeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceGiftcode $resource,
        GiftcodeFactory $giftcodeFactory,
        GiftcodeInterfaceFactory $dataGiftcodeFactory,
        GiftcodeCollectionFactory $giftcodeCollectionFactory,
        GiftcodeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->giftcodeFactory = $giftcodeFactory;
        $this->giftcodeCollectionFactory = $giftcodeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataGiftcodeFactory = $dataGiftcodeFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
    ) {
        /* if (empty($giftcode->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $giftcode->setStoreId($storeId);
        } */
        try {
            $giftcode->getResource()->save($giftcode);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the giftcode: %1',
                $exception->getMessage()
            ));
        }
        return $giftcode;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($giftcodeId)
    {
        $giftcode = $this->giftcodeFactory->create();
        $giftcode->getResource()->load($giftcode, $giftcodeId);
        if (!$giftcode->getId()) {
            throw new NoSuchEntityException(__('Giftcode with id "%1" does not exist.', $giftcodeId));
        }
        return $giftcode;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->giftcodeCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
    ) {
        try {
            $giftcode->getResource()->delete($giftcode);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Giftcode: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($giftcodeId)
    {
        return $this->delete($this->getById($giftcodeId));
    }
}
