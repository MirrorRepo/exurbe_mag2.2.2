<?php
namespace Favourite\FProduct\Observer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\Event\ObserverInterface;

class AddClassToBody implements ObserverInterface
{
    /** @var PageConfig */
    protected $pageConfig;
	  /** @var CustomerSession */
    protected $customerSession;

    public function __construct(PageConfig $pageConfig, CustomerSession $customerSession)
    {
        $this->pageConfig = $pageConfig;
        $this->customerSession = $customerSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // die("zxs");
        // if (!$this->customerSession->isLoggedIn()) {
        //     return;
        // }
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $storeHelper =$objectManager->get('Favourite\FProduct\Helper\Data');
        $currentStoreID = $storeHelper->getCurrentStoreId();
        $currentStoreCode = $storeHelper->getCurrentStoreCode();

        $this->pageConfig->addBodyClass('checkout_'.$currentStoreID.' checkout_'.$currentStoreCode);
    }
}
