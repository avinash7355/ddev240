<?php
namespace Brainvire\CustomWork\Model;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Framework\App\Config\ScopeConfigInterface;
/**
 * Class Consumer used to process OperationInterface messages.
 */
class Consumer extends ConsumerConfiguration
{
    const CONSUMER_NAME = "notifycustomer.massmail";

    const QUEUE_NAME = "notifycustomer.massmail";
    
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        // \Webkul\CustomWork\Helper\Email $emailHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager, 
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        ScopeConfigInterface $scopeConfig  
    ) {
        
        $this->jsonHelper = $jsonHelper;
        // $this->_emailHelper = $emailHelper;
        $this->messageManager = $messageManager;
        $this->orderRepository = $orderRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->scopeConfig = $scopeConfig;      
    }

    /**
     * consumer process start
     * @param string $messagesBody
     * @return string
     */
    public function process($request)
    {   
        try {
            $data = $this->jsonHelper->jsonDecode($request, true);
            
            foreach ($data as $productdata) {
                $parentProductId = $productdata['parent_product_id'];
                $parentProductUrl = $productdata['parent_product_url'];
                $parentProductName = $productdata['parent_product_name'];
                $childProductUrl = $productdata['child_product_url'];
                $childProductName = $productdata['child_product_name'];
                // get parant product
                $collection = $this->_productCollectionFactory->create()
                ->addFieldToFilter(
                [
                    'product_ids',
                    'product_ids',
                    'product_ids',
                    'product_ids'
                ],
                [
                    ['like' => $parentProductId],
                    ['like' => '%,'.$parentProductId.',%'],
                    ['like' => $parentProductId.',%'],
                    ['like' => '%,'.$parentProductId]
                ]);
                foreach ($collection as $preorderItem) {
                    $orderId = $preorderItem->getOrderId();
                    $order = $this->getOrderById($orderId);
                    $status = false;
                    $productForUrl = null;

                    foreach ($order->getAllVisibleItems() as $orderItem) {
                        $productForUrl = $orderItem->getProduct();
                        if ($orderItem->getChildrenItems()) {
                            foreach ($orderItem->getChildrenItems() as $childItem) {
                                if ($childItem->getProductId() == $parentProductId) {
                                    $status = true;
                                    break;
                                }
                            }

                            if ($status) {
                                break;
                            }
                        } else {
                            if ($orderItem->getProductId() == $parentProductId) {
                                $status = true;
                                break;
                            }
                        }
                    }                        
                    if ($status) {                            
                        $order = $orderItem->getOrder();
                        $orderId = $order->getId();
                        $emailId = $order->getCustomerEmail();
                        $customerName = $order->getCustomerName();
                        $customerId = $order->getCustomerId();                             
                        
                        $adminEmail = $this->scopeConfig->getValue('trans_email/ident_support/email');
                        if ($adminEmail == '') {
                            return;
                        }
                        $senderInfo = [];
                        $receiverInfo = [];
                        $senderInfo = [
                            'name' => 'Store Owner',
                            'email' => $adminEmail,
                        ];
                        $receiverInfo = [
                            'name' => $customerName,
                            'email' => $emailId,
                        ];
                        $templateVars = [
                            "parent_product_url" => $parentProductUrl,
                            "parent_product_name" => $parentProductName,
                            "child_product_url" => $childProductUrl,
                            "child_product_name" => $childProductName,
                            "customer_name" => $customerName
                        ];
                        // send data for email
                        // $this->_emailHelper->sendEmailToCustomer(
                        //     $templateVars,
                        //     $senderInfo,
                        //     $receiverInfo
                        // );
                        
                    }                        
                } 
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

    }
    /**
     * get order id
     * @param int $orderId
     * @return object
     */
    public function getOrderById($orderId)
    {
        return $this->orderRepository->get($orderId);
    }
}