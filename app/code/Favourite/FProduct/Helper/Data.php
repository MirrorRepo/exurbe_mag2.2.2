<?php
namespace Favourite\FProduct\Helper;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View as CustomerViewHelper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
  protected $_storeManager;
protected $_objectManager;
protected $_customerViewHelper;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerViewHelper $customerViewHelper,
\Magento\Store\Model\StoreManagerInterface $storeManager,
\Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->customerSession = $customerSession;
         $this->_customerViewHelper = $customerViewHelper;
        parent::__construct($context);
        $this->_storeManager = $storeManager;
$this->_objectManager = $objectManager;
    }

public function isLoggedIn() // You can use this fucntion in any phtml file
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCurrentStoreId() {
return $this->_storeManager->getStore()->getStoreId();
}
public function getCurrentStoreCode() {
return $this->_storeManager->getStore()->getCode();
}
}
