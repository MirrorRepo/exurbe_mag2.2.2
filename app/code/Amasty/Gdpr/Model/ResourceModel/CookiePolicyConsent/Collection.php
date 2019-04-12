<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent;

use Amasty\Gdpr\Model\CookiePolicyConsent;
use Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent as CookiePolicyResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @method CookiePolicyConsent[] getItems()
 */
class Collection extends AbstractCollection
{
    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _construct()
    {
        $this->_init(CookiePolicyConsent::class, CookiePolicyResource::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
