<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Controller\Cookie;

use Amasty\Gdpr\Model\CookieManager;
use Amasty\Gdpr\Model\CookiePolicyConsentLogger;
use Amasty\Gdpr\Model\Config;
use Amasty\Gdpr\Model\CookiePolicyConsent;
use Amasty\Gdpr\Model\Config\Source\CookiePolicyBar;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Allow extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CookieManager
     */
    private $cookieManager;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var CookiePolicyConsentLogger
     */
    private $consentLogger;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Context $context,
        CookieManager $cookieManager,
        Session $session,
        CookiePolicyConsentLogger $consentLogger,
        Config $config
    ) {
        parent::__construct($context);
        $this->cookieManager = $cookieManager;
        $this->session = $session;
        $this->consentLogger = $consentLogger;
        $this->config = $config;
    }

    public function execute()
    {
        $customerId = $this->session->getCustomerId();

        if ($customerId) {
            $barType = $this->config->getCookiePrivacyBar();
            $consentType = CookiePolicyConsent::NOTIFICATION_CONSENT;

            if ($barType != CookiePolicyBar::NOTIFICATION) {
                $consentType = CookiePolicyConsent::CONFIRMATION_ALLOWED_CONSENT;
            }
            $this->consentLogger->logCookieConsent(
                $customerId,
                $consentType,
                CookiePolicyConsent::STATUS_RECIEVED
            );
        }
        $this->cookieManager->setIsAllowCookies(true);
        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
