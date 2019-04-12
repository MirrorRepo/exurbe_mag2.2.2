<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model;

use Amasty\Gdpr\Api\CookiePolicyConsentRepositoryInterface;
use Amasty\Gdpr\Model\ResourceModel\CookiePolicyConsent as CookiePolicyConsentResource;
use Amasty\Gdpr\Model\Visitor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class CookiePolicyConsentLogger
{
    /**
     * @var CookiePolicyConsentRepositoryInterface
     */
    private $cookiePolicyConsentRepository;

    /**
     * @var CookiePolicyConsentFactory
     */
    private $cookiePolicyConsentFactory;

    /**
     * @var CookiePolicyConsentResource
     */
    private $cookiePolicyConsent;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Visitor
     */
    private $visitor;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct(
        CookiePolicyConsentRepositoryInterface $cookiePolicyConsentRepository,
        CookiePolicyConsentFactory $cookiePolicyConsentFactory,
        CookiePolicyConsentResource $cookiePolicyConsent,
        StoreManagerInterface $storeManager,
        Visitor $visitor,
        DateTime $date
    ) {
        $this->cookiePolicyConsentRepository = $cookiePolicyConsentRepository;
        $this->cookiePolicyConsentFactory = $cookiePolicyConsentFactory;
        $this->cookiePolicyConsent = $cookiePolicyConsent;
        $this->storeManager = $storeManager;
        $this->visitor = $visitor;
        $this->date = $date;
    }

    public function logCookieConsent($customerId, $type, $status)
    {
        if (!$customerId) {
            return;
        }
        $cookiePolicy = $this->cookiePolicyConsentRepository->getByCustomerIdAndStatus($customerId, $status);
        $website = $this->storeManager->getWebsite()->getId();
        $customerIp = $this->visitor->getRemoteIp();

        if ($cookiePolicy) {
            $cookiePolicy->setCustomerId($customerId)
                         ->setConsentType($type)
                         ->setConsentStatus($status)
                         ->setWebsite($website)
                         ->setDateRecieved($this->date->gmtDate())
                         ->setCustomerIp($customerIp);
            $this->cookiePolicyConsentRepository->save($cookiePolicy);

            return;
        }
        $consent = $this->cookiePolicyConsentFactory->create();
        $consent->setCustomerId($customerId)
                ->setConsentType($type)
                ->setConsentStatus($status)
                ->setWebsite($this->storeManager->getWebsite()->getId())
                ->setCustomerIp($this->visitor->getRemoteIp());
        $this->cookiePolicyConsentRepository->save($consent);
    }
}
