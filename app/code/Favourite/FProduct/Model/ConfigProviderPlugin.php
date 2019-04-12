<?php

namespace Favourite\FProduct\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartItemRepositoryInterface as QuoteItemRepository;


class ConfigProviderPlugin extends \Magento\Framework\Model\AbstractModel
{
    private $checkoutSession;
    private $quoteItemRepository;
    protected $scopeConfig;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteItemRepository $quoteItemRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Favourite\FProduct\Block\CProduct $block
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->quoteItemRepository = $quoteItemRepository;
          $this->block = $block;
    }


    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {
      $items = $result['totalsData']['items'];

     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
     for($i=0;$i<count($items);$i++){

         $quoteId = $items[$i]['item_id'];
         $quote = $objectManager->create('\Magento\Quote\Model\Quote\Item')->load($quoteId);
         $productId = $quote->getProductId();
         $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
         	$categories = $product->getCategoryIds();
          foreach($categories as $parent_category_id){
              $subcategory = $objectManager->create('Magento\Catalog\Model\Category')->load($parent_category_id);
              $subcats = $subcategory->getChildrenCategories();
              if(count($subcats) <= 0){
                $items[$i]['category']= $subcategory->getName();
              }
          }
        // $cat=$this->block->get_current_product_category_name($product);
        // $productFlavours = $cat;
         //$items[$i]['category'] = $productFlavours;
     }
     $result['totalsData']['items'] = $items;
     return $result;
        }

}
