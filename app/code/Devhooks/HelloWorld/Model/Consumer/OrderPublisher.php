<?php

namespace Devhooks\HelloWorld\Model\Consumer;

use Magento\Framework\MessageQueue\PublisherInterface ;
use Magento\Sales\Api\Data\OrderInterface;

class OrderPublisher
{
    
    const TOPIC_NAME = 'devhooks.custom.queue.topic.name';

    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $publisher;

    /**
     * @param \Magento\Framework\MessageQueue\PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }


    public function execute(OrderInterface $order)
    {
        $this->publisher->publish(self::TOPIC_NAME, $order);
    }
}