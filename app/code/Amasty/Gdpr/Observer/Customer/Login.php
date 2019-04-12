<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Observer\Customer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Amasty\Gdpr\Model\CookieManager;
use Amasty\Gdpr\Model\CookiePolicyConsentLogger;
use Amasty\Gdpr\Api\CookiePolicyConsentRepositoryInterface;
use Amasty\Gdpr\Model\Config;

class Login implements ObserverInterface
{
    /**
     * @var CookieManager
     */
    private $cookieManager;

    /**
     * @var CookiePolicyConsentLogger
     */
    private $consentLogger;

    /**
     * @var CookiePolicyConsentRepositoryInterface
     */
    private $consentRepository;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        CookieManager $cookieManager,
        CookiePolicyConsentLogger $consentLogger,
        CookiePolicyConsentRepositoryInterface $consentRepository,
        Config $config
    ) {
        $this->cookieManager = $cookieManager;
        $this->consentLogger = $consentLogger;
        $this->consentRepository = $consentRepository;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     *
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        $cookie = $this->cookieManager->isAllowCookies();

        if ($cookie === null) {
            return;
        }
        $customerId = $observer->getData('customer')->getData('entity_id');
        $cookieStatus = \Amasty\Gdpr\Model\CookiePolicyConsent::STATUS_RECIEVED;
        $cookieType = \Amasty\Gdpr\Model\CookiePolicyConsent::CONFIRMATION_ALLOWED_CONSENT;
        $barType = $this->config->getCookiePrivacyBar();

        if ($cookie == 0) {
            $cookieType = \Amasty\Gdpr\Model\CookiePolicyConsent::CONFIRMATION_DISALLOWED_CONSENT;
        } elseif ($barType == \Amasty\Gdpr\Model\Config\Source\CookiePolicyBar::NOTIFICATION) {
            $cookieType = \Amasty\Gdpr\Model\CookiePolicyConsent::NOTIFICATION_CONSENT;
        }
        $this->consentLogger->logCookieConsent($customerId, $cookieType, $cookieStatus);
    }
}
