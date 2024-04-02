<?php
namespace Brainvire\ProductCustomize\Plugin;

class PriceChoice
{
       public function afterGetProductPrice($subject, $result)
    {
        $suffix = ' / Year'; 
        $result = str_replace('</div>', $suffix , $result);

        return $result;
    }
}
