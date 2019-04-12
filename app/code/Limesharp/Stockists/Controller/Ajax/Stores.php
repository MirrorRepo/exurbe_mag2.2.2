<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Limesharp\Stockists\Controller\Ajax;

use Limesharp\Stockists\Model\ResourceModel\Stores\CollectionFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Responsible for loading page content.
 *
 * This is a basic controller that only loads the corresponding layout file. It may duplicate other such
 * controllers, and thus it is considered tech debt. This code duplication will be resolved in future releases.
 */
class Stores extends \Magento\Framework\App\Action\Action
{
    
    protected $resultJsonFactory;    
    
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CollectionFactory $collectionFactory 
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    
    /**
     * Load the page defined in view/frontend/layout/stockists_index_index.xml
     *
     * @return \Magento\Framework\Controller\Result\JsonFactory
     */
    public function execute()
    {        
        $collection = $this->collectionFactory->create()->getData();
        $json = [];
        foreach ($collection as $stockist) {
            $json[] = $stockist;
        }
        return  $this->resultJsonFactory->create()->setData($json);
    }
}
