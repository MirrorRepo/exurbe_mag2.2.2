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

use Magetwo\Giftcard\Api\Data\GiftcodeInterface;

class Giftcode extends \Magento\Framework\Model\AbstractModel implements GiftcodeInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magetwo\Giftcard\Model\ResourceModel\Giftcode');
    }

    /**
     * Get giftcode_id
     * @return string
     */
    public function getGiftcodeId()
    {
        return $this->getData(self::GIFTCODE_ID);
    }

    /**
     * Set giftcode_id
     * @param string $giftcodeId
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     */
    public function setGiftcodeId($giftcodeId)
    {
        return $this->setData(self::GIFTCODE_ID, $giftcodeId);
    }

    /**
     * Get code
     * @return string
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set code
     * @param string $code
     * @return \Magetwo\Giftcard\Api\Data\GiftcodeInterface
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }
}
