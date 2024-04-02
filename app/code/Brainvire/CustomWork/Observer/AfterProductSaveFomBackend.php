<?php
namespace Brainvire\CustomWork\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;


class AfterProductSave implements ObserverInterface
{
     public function __construct( 
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ProductRepositoryInterface $productRepository,               
        \Magento\Framework\MessageQueue\PublisherInterface $publisher,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager        
    ) {
        $this->_messageManager = $messageManager;
        $this->productRepository = $productRepository;        
        $this->publisher = $publisher;
        $this->jsonHelper = $jsonHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * execute
     * @param Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/' . 'hidro_debug_message_queue.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug(json_encode($observer->getData()));
        echo "aionv";die; 
        try {
            $controller = $observer->getController();
            $childProduct = $observer->getProduct();
            $params = $controller->getRequest()->getParams();
            $wkParentProductSku = $params['product']['wk_parent_product_sku'];
            // you can create a product attribute text field 'wk_parent_product_sku' for paraent product
            if ($wkParentProductSku) {
                
                $paretntProduct = $this->productRepository->get($wkParentProductSku); 
                $parentProductId = $paretntProduct->getEntityId();
                $childProductId = $childProduct->getEntityId();            
                
                $stores = $this->storeManager->getStores();
                $storeId = 0;
                foreach ($stores as $store) {
                    $storeId = $store->getId();
                    if ($storeId > 0) {
                        break;
                    }
                } 
                $parentProductUrl = $paretntProduct->setStoreId($storeId)->getProductUrl();
                $parentProductName = $paretntProduct->getName();
                $childProductUrl = $childProduct->setStoreId($storeId)->getProductUrl();
                $childProductName = $childProduct->getName();
                $details[] = [
                    "parent_product_id" => $parentProductId,
                    "parent_product_url" => $parentProductUrl,
                    "parent_product_name" => $parentProductName,
                    "child_product_url" => $childProductUrl,
                    "child_product_name" => $childProductName,                    
                    "child_product_id" => $childProductId
                ];
                $this->publisher->publish(
                    'notifycustomer.massmail',
                    $this->jsonHelper->jsonEncode($details)
                ); 
                if ($details) {
                    $this->_messageManager->addSuccess(
                        __('Message is added to queue!!')
                    );
                } else {
                    $this->_messageManager->addSuccess(
                        __('Something Went Wrong!!')
                    );
                }  
            }
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }  
    }
}