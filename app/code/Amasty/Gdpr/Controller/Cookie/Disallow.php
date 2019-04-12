<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Controller\Cookie;

use Amasty\Gdpr\Model\CookieManager;
use Amasty\Gdpr\Model\CookiePolicyConsentLogger;
use Amasty\Gdpr\Model\CookiePolicyConsent;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Disallow extends \Magento\Framework\App\Action\Action
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

    public function __construct(
        Context $context,
        CookieManager $cookieManager,
        Session $session,
        CookiePolicyConsentLogger $consentLogger
    ) {
        parent::__construct($context);
        $this->cookieManager = $cookieManager;
        $this->session = $session;
        $this->consentLogger = $consentLogger;
    }

    public function execute()
    {
        $customerId = $this->session->getCustomerId();

        if ($customerId) {
            $this->consentLogger->logCookieConsent(
                $customerId,
                CookiePolicyConsent::CONFIRMATION_DISALLOWED_CONSENT,
                CookiePolicyConsent::STATUS_RECIEVED
            );
        }
        $this->cookieManager->setIsAllowCookies(false);
        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
