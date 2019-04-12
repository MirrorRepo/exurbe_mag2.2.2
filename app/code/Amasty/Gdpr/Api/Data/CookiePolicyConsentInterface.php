<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Api\Data;

interface CookiePolicyConsentInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const ID = 'id';
    const CUSTOMER_ID = 'customer_id';
    const CONSENT_TYPE = 'consent_type';
    const DATE_RECIEVED ='date_recieved';
    const CONSENT_STATUS = 'consent_status';
    const WEBSITE = 'website';
    const CUSTOMER_IP = 'customer_ip';
    /**#@-*/

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $id
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setCustomerId($id);

    /**
     * @return string
     */
    public function getConsentType();

    /**
     * @param string $consentType
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setConsentType($consentType);

    /**
     * @return string
     */
    public function getDateRecieved();

    /**
     * @param string $date
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setDateRecieved($date);

    /**
     * @return int
     */
    public function getConsentStatus();

    /**
     * @param int $status
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setConsentStatus($status);

    /**
     * @return int
     */
    public function getWebsite();

    /**
     * @param int $websiteId
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setWebsite($websiteId);

    /**
     * @return string
     */
    public function getCustomerIp();

    /**
     * @param string $ip
     *
     * @return \Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface
     */
    public function setCustomerIp($ip);
}
