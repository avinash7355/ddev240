<?php
namespace Company\Vendor\Model\MessageQueue;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Consumer used to process OperationInterface messages.
 */
class UpdateProduct extends ConsumerConfiguration {

	const TOPIC_NAME = "TOPIC.NAME.HERE";

	public function __construct(
		CollectionFactory $collection,
        ProductAction $action,
        StoreManagerInterface $storeManager
	) {
		$this->productCollection = $collection;
        $this->productAction = $action;
        $this->storeManager = $storeManager;
	}

	/**
	 * Consumer process start
	 * 
	 * @param mixed $request
	 * @return void
	 */
	public function process($request) {
		$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/mysql_message_queue.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);

		$data = $this->jsonHelper->jsonDecode($request, true);
		$logger->info(print_r($data, true));

		try {
			$msgQueueArr['mysql_mq'] = 1;
			$productId = 1;
			$storeId = $this->storeManager->getStore()->getId();
			$this->productAction->updateAttributes([$productId], $msgQueueArr, $storeId);
		
		} catch (\Exception $e) {
			$logger->info("Error update.product.attribute: " . $e->getMessage());
		}
	}
}
