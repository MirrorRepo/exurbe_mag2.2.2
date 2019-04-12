<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Api;

/**
 * @api
 */
interface CookiePolicyConsentRepositoryInterface
{
    /**
     * Save
     *
     * @param \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface $cookiePolicyConsent
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function save(\Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface $cookiePolicyConsent);

    /**
     * Get by id
     *
     * @param int $id
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete
     *
     * @param \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface $cookiePolicyConsent
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface $cookiePolicyConsent);

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id);

    /**
     * Lists
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $id
     * @param int $status
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface|bool|array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCustomerIdAndStatus($id, $status);
}
