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

interface GiftcodeInterface
{

    const CODE = 'code';
    const GIFTCODE_ID = 'giftcode_id';


    /**
     * Get giftcode_id
     * @return string|null
     */
    public function getGiftcodeId();

    /**
     * Set giftcode_id
     * @param string $giftcode_id
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     */
    public function setGiftcodeId($giftcodeId);

    /**
     * Get code
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     * @param string $code
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     */
    public function setCode($code);
}
