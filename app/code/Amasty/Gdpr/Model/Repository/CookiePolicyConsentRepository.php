<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model\Repository;

use Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface;
use Amasty\Gdpr\Api\CookiePolicyConsentRepositoryInterface;
use Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent as CookiePolicyConsentResource;
use Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent\Collection;
use Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent\CollectionFactory;
use Amasty\Gdpr\Model\CookiePolicyConsentFactory;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CookiePolicyConsentRepository implements CookiePolicyConsentRepositoryInterface
{
    /**
     * @var BookmarkSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CookiePolicyConsentFactory
     */
    private $cookiePolicyConsentFactory;

    /**
     * @var CookiePolicyConsentResource
     */
    private $cookiePolicyConsentResource;

    /**
     * Model data storage
     *
     * @var array
     */
    private $cookiePolicyConsents;

    /**
     * @var CollectionFactory
     */
    private $cookiePolicyConsentCollectionFactory;

    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        CookiePolicyConsentFactory $cookiePolicyConsentFactory,
        CookiePolicyConsentResource $cookiePolicyConsentResource,
        CollectionFactory $cookiePolicyConsentCollectionFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->cookiePolicyConsentFactory = $cookiePolicyConsentFactory;
        $this->cookiePolicyConsentResource = $cookiePolicyConsentResource;
        $this->cookiePolicyConsentCollectionFactory = $cookiePolicyConsentCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(CookiePolicyConsentInterface $cookiePolicyConsent)
    {
        try {
            if ($cookiePolicyConsent->getId()) {
                $cookiePolicyConsent = $this->getById($cookiePolicyConsent->getId())
                    ->addData($cookiePolicyConsent->getData());
            }
            $this->cookiePolicyConsentResource->save($cookiePolicyConsent);
            unset($this->cookiePolicyConsents[$cookiePolicyConsent->getId()]);
        } catch (\Exception $e) {
            if ($cookiePolicyConsent->getId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save cookiePolicyConsent with ID %1. Error: %2',
                        [$cookiePolicyConsent->getId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new withConsent. Error: %1', $e->getMessage()));
        }

        return $cookiePolicyConsent;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        if (!isset($this->cookiePolicyConsents[$id])) {
            /** @var \Amasty\Gdpr\Model\cookiePolicyConsent $cookiePolicyConsent */
            $cookiePolicyConsent = $this->cookiePolicyConsentFactory->create();
            $this->cookiePolicyConsentResource->load($cookiePolicyConsent, $id);
            if (!$cookiePolicyConsent->getId()) {
                throw new NoSuchEntityException(__('CookiePolicyConsent with specified ID "%1" not found.', $id));
            }
            $this->cookiePolicyConsents[$id] = $cookiePolicyConsent;
        }

        return $this->cookiePolicyConsents[$id];
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        $cookiePolicyConsentModel = $this->getById($id);

        return $this->delete($cookiePolicyConsentModel);
    }

    /**
     * @inheritdoc
     */
    public function delete(CookiePolicyConsentInterface $cookiePolicyConsent)
    {
        try {
            $this->cookiePolicyConsentResource->delete($cookiePolicyConsent);
            unset($this->cookiePolicyConsents[$cookiePolicyConsent->getId()]);
        } catch (\Exception $e) {
            if ($cookiePolicyConsent->getId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove CookiePolicyConsent with ID %1. Error: %2',
                        [$cookiePolicyConsent->getId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove CookiePolicyConsent. Error: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent\Collection $cookiePolicyConsentCollection */
        $cookiePolicyConsentCollection = $this->cookiePolicyConsentCollectionFactory->create();
        // Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $cookiePolicyConsentCollection);
        }
        $searchResults->setTotalCount($cookiePolicyConsentCollection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            $this->addOrderToCollection($sortOrders, $cookiePolicyConsentCollection);
        }
        $cookiePolicyConsentCollection->setCurPage($searchCriteria->getCurrentPage());
        $cookiePolicyConsentCollection->setPageSize($searchCriteria->getPageSize());
        $cookiePolicyConsents = [];
        /** @var CookiePolicyConsentInterface $cookiePolicyConsent */
        foreach ($cookiePolicyConsentCollection->getItems() as $cookiePolicyConsent) {
            $cookiePolicyConsents[] = $this->getById($cookiePolicyConsent->getId());
        }
        $searchResults->setItems($cookiePolicyConsents);

        return $searchResults;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection  $cookiePolicyConsentCollection
     *
     * @return void
     */
    private function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $cookiePolicyConsentCollection)
    {
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $cookiePolicyConsentCollection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
        }
    }

    /**
     * Helper function that adds a SortOrder to the collection.
     *
     * @param SortOrder[] $sortOrders
     * @param Collection  $cookiePolicyConsentCollection
     *
     * @return void
     */
    private function addOrderToCollection($sortOrders, Collection $cookiePolicyConsentCollection)
    {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $cookiePolicyConsentCollection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_DESC) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getByCustomerIdAndStatus($id, $status = null)
    {
        /** @var \Amasty\Gdpr\Model\cookiePolicyConsentCollection $cookiePolicyCollection */
        $cookiePolicyCollection = $this->cookiePolicyConsentCollectionFactory->create();
        $cookiePolicy = $cookiePolicyCollection->addFieldToFilter('customer_id', $id);

        if ($status !== null) {
            $cookiePolicy = $cookiePolicyCollection->addFieldToFilter('consent_status', $status)
                ->getFirstItem();
        } else {
            $cookiePolicy = $cookiePolicyCollection->getItems();
        }

        if (!$cookiePolicy) {
            throw new NoSuchEntityException(__('CookiePolicyConsent with specified ID "%1" not found.', $id));
        }

        return $cookiePolicy ?: false;
    }
}
