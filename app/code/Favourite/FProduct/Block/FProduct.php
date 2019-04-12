<?php
namespace Favourite\FProduct\Block;

class FProduct extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;

    protected $_productVisibility;
        protected $_cartHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
          \Magento\Framework\Url\Helper\Data $urlHelper,
              \Magento\Checkout\Helper\Cart $cartHelper,
       // \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
        $this->urlHelper = $urlHelper;
        $this->_cartHelper = $cartHelper;
       // $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
    public function getProductCollection() {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('favourite', '1');
        $collection->addWebsiteFilter();

        // filter current store products
        $collection->addStoreFilter();

        // set visibility filter
        $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());

        // fetching only 5 products
        $collection->setPageSize(5);

        return $collection;
    }

    public function getProductImageUrl($product) {
        $mediaUrl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return '<img src='.$mediaUrl.'catalog/product'.$product->getImage().' alt="" style="height:330px"/>';
        

    }

	public function getStoreURL()
    {
		$baseUrl = $this->_storeManager->getStore()->getBaseUrl();
		return $baseUrl;
        //return $this->_storeManager->getStore()->getCode();
    }

    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
{
$url = $this->getAddToCartUrl($product);
return [
    'action' => $url,
    'data' => [
        'product' => $product->getEntityId(),
        \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
            $this->urlHelper->getEncodedUrl($url),
    ]
];
}


public function getAddToCartUrl($product, $additional = [])
{
    return $this->_cartHelper->getAddUrl($product, $additional);
}

public function isRedirectToCartEnabled()
{
    return $this->_scopeConfig->getValue(
        'checkout/cart/redirect_to_cart',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    );
}


}
