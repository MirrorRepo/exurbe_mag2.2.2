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

namespace Magetwo\Giftcard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface GiftcodeRepositoryInterface
{


    /**
     * Save Giftcode
     * @param \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
    );

    /**
     * Retrieve Giftcode
     * @param string $giftcodeId
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($giftcodeId);

    /**
     * Retrieve Giftcode matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Giftcode
     * @param \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Magetwo\Giftcard\Api\Data\GiftcodeInterface $giftcode
    );

    /**
     * Delete Giftcode by ID
     * @param string $giftcodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($giftcodeId);
}
