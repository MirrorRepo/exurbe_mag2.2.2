<?php
/* 
 * @package Credevlabz/Testimonials
 * @category Controller
 * @author Aman Srivastava <http://amansrivastava.in>
 *
 */

namespace Magenest\ImageGallery\Controller\Index;

use \Magento\Framework\App\Action\Action;

class Press extends Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    protected $helperData;
    protected $scopeConfig;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(\Magento\Framework\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                                \Magenest\ImageGallery\Helper\Data $helperData)
    {
		$this->helperData = $helperData;
        $this->resultPageFactory = $resultPageFactory;
       // $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Testimonials Index, shows a list of recent Testimonials.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
    	echo $this->helperData->getGeneralConfig('verticaldimension');
    	//echo $this->scopeConfig->getValue('imagegallery/general/verticaldimension', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    	//die("rishi");

		if ($this->getRequest()->isPost()) {
			$post = $this->getRequest()->getPostValue();
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$baseUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl();
			$refererUrl = explode('?', $this->_redirect->getRefererUrl())[0];
			$previusPageNoError = $baseUrl.'press';			
			$passPress =  $post['passwordpress'];
			$pass ="123456";
			if(!empty($passPress))
			{
				if($passPress == $pass)
				{
					//$this->_config_imagegallery->

			        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
					$catalogSession = $objectManager->create('Magento\Catalog\Model\Session');
					$catalogSession->setPressLogin('1');					
					return $this->_redirect($previusPageNoError);
					
				}
				else
				{
					$previusPage = $previusPageNoError . '?error=1';
					$this->PressLoginFail($previusPage);
				}
				
				
			}
			else{
				$previusPage = $previusPageNoError . '?error=1';
				$this->messageManager->addErrorMessage(__('Press pswword is required'));
                return $this->_redirect($previusPage);
			}
		}
        return $this->resultPageFactory->create();
    }
	public function PressLoginFail($previusPage)
	{
		
		$this->messageManager->addErrorMessage(__('Invalid press password.'));
        return $this->_redirect($previusPage);
	}

	
}