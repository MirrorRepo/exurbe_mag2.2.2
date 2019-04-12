<?php

namespace Favourite\FProduct\Model;
class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
   public function getConfig()
   {
       $output['test_config'] = 'Test Config';
       // $items = $output['totalsData']['items'];
       // foreach($items as $item) {
       //      $result['category_RG']="default";
       // }
       return $output;
   }


}
