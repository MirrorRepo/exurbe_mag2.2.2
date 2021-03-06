<?php

namespace Dotdigitalgroup\Email\Block\Adminhtml\Config\Developer;

class Resetorders extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @var string
     */
    public $buttonLabel = 'Run Now';

    /**
     * @param string $buttonLabel
     *
     * @return $this
     */
    public function setButtonLabel($buttonLabel)
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    /**
     * Get the button and scripts contents.
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    public function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $query = [
            '_query' => [
                'from' => '',
                'to' => '',
                'tp' => ''
            ]
        ];
        $url = $this->_urlBuilder->getUrl('dotdigitalgroup_email/run/ordersreset', $query);

        return $this->getLayout()
            ->createBlock(\Magento\Backend\Block\Widget\Button::class)
            ->setType('button')
            ->setId($element->getId())
            ->setLabel($this->buttonLabel)
            ->setOnClick("window.location.href='" . $url . "'")
            ->toHtml();
    }
}
