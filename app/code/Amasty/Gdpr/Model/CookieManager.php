<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model;

use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\SessionManagerInterface;

class CookieManager
{
    /**#@+*/
    const ALLOW_COOKIES = 'am_allow_cookies';
    /**#@-*/

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param bool $status
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setIsAllowCookies($status = true)
    {
        $cookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain())
            ->setDurationOneYear();

        try {
            $this->cookieManager->setPublicCookie(self::ALLOW_COOKIES, (int)$status, $cookieMetadata);
        } catch (\Exception $e) {
            null;
        }
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function unsetIsAllowCookies()
    {
        $this->cookieManager->deleteCookie(
            self::ALLOW_COOKIES,
            $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain())
        );
    }

    /**
     * @return string|null
     */
    public function isAllowCookies()
    {
        return $this->cookieManager->getCookie(self::ALLOW_COOKIES);
    }

    public function deleteCookies($cookies)
    {
        try {
            foreach ($cookies as $cookie) {
                $this->cookieManager->deleteCookie($cookie);
            }
        } catch (\Exception $e) {
            null;
        }
    }
}
