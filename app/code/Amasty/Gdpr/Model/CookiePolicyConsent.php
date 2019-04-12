<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model;

use Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface;
use Magento\Framework\Model\AbstractModel;

class CookiePolicyConsent extends AbstractModel implements CookiePolicyConsentInterface
{
    const STATUS_RECIEVED = 1;
    const STATUS_REVOKED = 0;
    const NOTIFICATION_CONSENT = 0;
    const CONFIRMATION_ALLOWED_CONSENT = 1;
    const CONFIRMATION_DISALLOWED_CONSENT = 2;

    public function _construct()
    {
        $this->_init(ResourceModel\CookiePolicyConsent::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_getData(CookiePolicyConsent::ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->setData(CookiePolicyConsent::ID, $id);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId()
    {
        return $this->_getData(CookiePolicyConsent::CUSTOMER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId($id)
    {
        $this->setData(CookiePolicyConsent::CUSTOMER_ID, $id);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getConsentType()
    {
        return $this->_getData(CookiePolicyConsentInterface::CONSENT_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setConsentType($consentType)
    {
        $this->setData(CookiePolicyConsentInterface::CONSENT_TYPE, $consentType);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDateRecieved()
    {
        return $this->_getData(CookiePolicyConsentInterface::DATE_RECIEVED);
    }

    /**
     * @inheritdoc
     */
    public function setDateRecieved($date)
    {
        $this->setData(CookiePolicyConsentInterface::DATE_RECIEVED, $date);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getConsentStatus()
    {
        return $this->_getData(CookiePolicyConsentInterface::CONSENT_STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setConsentStatus($status)
    {
        $this->setData(CookiePolicyConsentInterface::CONSENT_STATUS, $status);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getWebsite()
    {
        return $this->_getData(CookiePolicyConsentInterface::WEBSITE);
    }

    /**
     * @inheritdoc
     */
    public function setWebsite($websiteId)
    {
        $this->setData(CookiePolicyConsentInterface::WEBSITE, $websiteId);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerIp()
    {
        return $this->_getData(CookiePolicyConsentInterface::CUSTOMER_IP);
    }

    /**
     * @inheritdoc
     */
    public function setCustomerIp($ip)
    {
        $this->setData(CookiePolicyConsentInterface::CUSTOMER_IP, $ip);

        return $this;
    }
}
