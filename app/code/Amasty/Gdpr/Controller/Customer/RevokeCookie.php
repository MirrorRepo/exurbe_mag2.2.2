<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Controller\Customer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Amasty\Gdpr\Model\CookieManager;
use Amasty\Gdpr\Model\CookiePolicyConsentLogger;
use Amasty\Gdpr\Api\CookiePolicyConsentRepositoryInterface;
use Amasty\Gdpr\Model\CookiePolicyConsent;

class RevokeCookie extends Action
{
    /**
     * @var FormKeyValidator
     */
    private $formKeyValidator;

    /**
     * @var CookieManager
     */
    private $cookieManager;

    /**
     * @var CookiePolicyConsentLogger
     */
    private $consentLogger;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var CookiePolicyConsentRepositoryInterface
     */
    private $consentRepository;

    public function __construct(
        Context $context,
        FormKeyValidator $formKeyValidator,
        CookieManager $cookieManager,
        CookiePolicyConsentLogger $consentLogger,
        CookiePolicyConsentRepositoryInterface $consentRepository,
        Session $session
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->cookieManager = $cookieManager;
        $this->consentLogger = $consentLogger;
        $this->session = $session;
        $this->consentRepository = $consentRepository;
    }

    /**
     * Removes am_allow_cookies cookie from customer
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(__('Invalid Form Key. Please refresh the page.'));
            $this->_redirect('*/*/settings');

            return;
        }
        $customerId = $this->session->getCustomerId();
        $policyTypeToRevoke = $this->consentRepository
            ->getByCustomerIdAndStatus(
                $customerId,
                CookiePolicyConsent::STATUS_RECIEVED
            )->getConsentType();

        if ($this->cookieManager->isAllowCookies() !== null) {
            $this->cookieManager->unsetIsAllowCookies();
            $this->consentLogger->logCookieConsent(
                $customerId,
                $policyTypeToRevoke,
                CookiePolicyConsent::STATUS_REVOKED
            );
            $this->messageManager->addSuccessMessage(__('Cookies has been revoked'));
        } else {
            $this->messageManager->addNoticeMessage(__('No cookie decision has been made yet'));
        }

        $this->_redirect('*/*/settings');
    }
}
