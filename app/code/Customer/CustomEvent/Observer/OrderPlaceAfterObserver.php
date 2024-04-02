<?php

namespace Customer\CustomEvent\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class OrderPlaceAfterObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customerName = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();

        // Display a Thank You message with the customer's name
        $message = "Thank you, {$customerName}, for your order!";
        // You can customize the message as needed
        // Use Magento's message manager to display the message
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $messageManager = $objectManager->create(\Magento\Framework\Message\ManagerInterface::class);
        $messageManager->addSuccessMessage($message);
    }
}
