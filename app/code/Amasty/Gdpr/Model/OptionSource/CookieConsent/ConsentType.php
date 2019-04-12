<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model\OptionSource\CookieConsent;

use Magento\Framework\Option\ArrayInterface;
use Amasty\Gdpr\Model\CookiePolicyConsent;

class ConsentType implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => CookiePolicyConsent::NOTIFICATION_CONSENT,
                'label'=> __('Notification Bar Consent')
            ],
            [
                'value' => CookiePolicyConsent::CONFIRMATION_ALLOWED_CONSENT,
                'label'=> __('Confirmation Bar Allowed Optional Cookies')
            ],
            [
                'value' => CookiePolicyConsent::CONFIRMATION_DISALLOWED_CONSENT,
                'label'=> __('Confirmation Bar Disallowed Optional Cookies')
            ]
        ];
    }
}
