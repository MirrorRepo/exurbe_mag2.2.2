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

namespace Magetwo\Giftcard\Api\Data;

interface GiftcodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Giftcode list.
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface[]
     */
    public function getItems();

    /**
     * Set code list.
     * @param \Magetwo\Giftcard\Api\Data\GiftcodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
