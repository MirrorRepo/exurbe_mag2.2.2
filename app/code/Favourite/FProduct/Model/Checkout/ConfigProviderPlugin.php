<?php

public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
{
    $items = $result['totalsData']['items'];
    foreach($items as $item) {
        //Your code here
    }

    return $result;
}
