<?php
namespace Database\Check\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;

class DisplayCustomerName implements ObserverInterface
{
    protected $customerSession;

    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function execute(Observer $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/' . 'hidro_debug_message_queue.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug(json_encode($observer->getData()));
        return true;
    }
}
