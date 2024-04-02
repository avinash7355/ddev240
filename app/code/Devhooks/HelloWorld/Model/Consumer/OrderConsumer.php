<?php

namespace Devhooks\HelloWorld\Model\Consumer;

use Magento\Sales\Api\Data\OrderInterface;

class OrderConsumer
{
    
    /**
     * @param OrderInterface $order
     */
    public function processMessage(OrderInterface $order)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/' . 'hidro_debug_message_queue.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug(json_encode($order->getData()));
        return true;
    }
}