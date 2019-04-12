<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\CustomerData;

use Magento\Quote\Model\Quote\Item;

/**
 * Abstract item
 *
 * @api
 */
abstract class AbstractItem implements ItemInterface
{
    /**
     * @var Item
     */
    protected $item;

    /**
     * {@inheritdoc}
     */
    public function getItemData(Item $item)
    {
        $this->item = $item;
        $cat_name="";
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categories =  $this->item->getProduct()->getCategoryIds();
        foreach($categories as $category){
        $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
        $subcats = $cat->getChildrenCategories();
        if(count($subcats) <= 0){
          $cat_name= $cat->getName();
        }
        }
        $result['manu'] = $cat_name;
        return \array_merge(
            ['product_type' => $item->getProductType()],$result,
            $this->doGetItemData()
        );
    }

    /**
     * Get item data. Template method
     *
     * @return array
     */
    abstract protected function doGetItemData();
}
