<?php
namespace Favourite\FProduct\Block;

//use Magento\Framework\ObjectManagerInterface;
class CProduct extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;

    protected $_productVisibility;

    private $_objectManager;

    public function __construct(
      //  \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
  //  \Magento\Framework\ObjectManagerInterface $objectmanager,

       // \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
      parent::__construct($context, $data);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
		    $this->categoryFactory = $categoryFactory;
  //  $this->_objectManager = $objectmanager;
       // $this->_storeManager = $storeManager;
        // parent::__construct($context, $data);
    }

    public function getCategoryProduct($catIds=null) {



		//$categories = [2,3,5];//category ids array
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $catIds]);
         $collection->addAttributeToFilter('status', array('eq' => 1) );
        $collection->addAttributeToSort('position_sort', 'ASC');

        // $collection->setPageSize(20);


        return $collection;

    }

	public function getProductImageUrl($product) {
        $mediaUrl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        echo $mediaUrl;
		//return '<img  src='.$mediaUrl.'catalog/product'.$product->getImage().' alt="" />';

    }

    public function get_current_product_category_name($_product)
    {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categories = $_product->getCategoryIds();
        //print_r($categories);//die('rishi');
        if(empty($categories)){
          return "";
        }else {
          if($categories[0]==37 && isset($categories[1]))
          {
            $categoryId = is_array($categories)?$categories[1]:$categories;

          }elseif ($categories[0]==47 && isset($categories[1])) {
              $categoryId = is_array($categories)?$categories[1]:$categories;

          }else{
          $categoryId = is_array($categories)?$categories[0]:$categories;
         }
         //echo $categoryId;
          $category = $objectManager->create('Magento\Catalog\Model\Category')
          ->load($categoryId);
          // $arr_find_cat=explode('/',$category->getPath());
          // echo $end_cat=end($arr_find_cat);
          return $category->getName();
        }



    }

    public function get_rating($product="")
    {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $product = $product!=""?$product:$objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product
       $RatingOb = $objectManager->create('Magento\Review\Model\Rating')->getEntitySummary($product->getId());
      $ratings=0;
    
      if($RatingOb->getSum()!="" && $RatingOb->getSum()!=0){
        $ratings = $RatingOb->getSum()/$RatingOb->getCount();

      }


      return $ratings;
    }

    public function customer_log_in()
    {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $customerSession =$objectManager->get('Magento\Customer\Model\Session');
      return $customerSession->isLoggedIn();
    }


}
