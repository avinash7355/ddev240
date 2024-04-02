<?php
namespace Database\Check\Plugin;
class PriceChg
{
    public function afterAddProduct(
        \Magento\Quote\Model\Quote $subject,
        $result,
        $productInfo,
        $requestInfo = null
    ) {
        if ($result instanceof \Magento\Quote\Model\Quote\Item) {
            $newPrice = $result->getProduct()->getPrice() * 10; 
            
            $result->setCustomPrice($newPrice);
            $result->setOriginalCustomPrice($newPrice);
        }
        
        return $result;
    }
}
