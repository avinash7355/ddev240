<?php 
 namespace Database\Check\Model;
 class Product extends \Magento\Catalog\Model\Product{

 	public function getName()
 {
 	return 'prefernce';
 }

 	// public function aftergetName(\Magento\Catalog\Model\Product $product, $name){
 	// 	$price=$product->getData(key: 'price');
 	// 	if($price <60){
 	// 		$name .= " So cheap	";
 	// 	}
 	// 	else{
 	// 		$name .= "so costly";
 	// 	}
 	// 	return $name;


 	}
 
	