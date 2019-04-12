<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Model\OptionSource\CookieConsent;

use Magento\Framework\Option\ArrayInterface;
use Amasty\Gdpr\Model\CookiePolicyConsent;

class ConsentStatus implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => CookiePolicyConsent::STATUS_RECIEVED, 'label'=> __('Received')],
            ['value' => CookiePolicyConsent::STATUS_REVOKED, 'label'=> __('Revoked')]
        ];
    }
}
