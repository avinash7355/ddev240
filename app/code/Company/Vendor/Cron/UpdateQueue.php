<?php
namespace Company\Vendor\Cron;

use Company\Vendor\Model\MessageQueue\UpdateProduct;

class UpdateQueue {

	public function __construct(
		\Magento\Framework\Json\Helper\Data $jsonHelper,
		\Magento\Framework\MessageQueue\PublisherInterface $publisher
	) {
		$this->_publisher = $publisher;
		$this->jsonHelper = $jsonHelper;
	}

	public function execute() {

		$details[] = [
			"any_informatic_index" => "value",
		];

		$this->_publisher->publish(
			UpdateProduct::TOPIC_NAME,
			$this->jsonHelper->jsonEncode($details)
		);
		
	}	
}
