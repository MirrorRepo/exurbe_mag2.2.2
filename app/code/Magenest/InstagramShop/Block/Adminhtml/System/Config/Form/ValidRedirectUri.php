<?php
namespace Magenest\InstagramShop\Block\Adminhtml\System\Config\Form;

use Magenest\InstagramShop\Model\Client;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\ObjectManager;

class ValidRedirectUri extends Field {

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $redirectUri = ObjectManager::getInstance()->create(Client::class)->getRedirectUri();
        $element->setValue($redirectUri);
        $element->setReadonly(true);
        return parent::_getElementHtml($element);
    }
}