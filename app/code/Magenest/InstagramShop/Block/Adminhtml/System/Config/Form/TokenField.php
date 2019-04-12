<?php
namespace Magenest\InstagramShop\Block\Adminhtml\System\Config\Form;

/**
 * Class TokenField
 * @package Magenest\InstagramShop\Block\System\Config\Form
 */
class TokenField extends InputField
{
    /**
     * create element for Access token field in store configuration
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setReadonly(true);
        return parent::_getElementHtml($element);
    }
}
