<?php
namespace Customer\Event\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class CustomObserver implements ObserverInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observerData = $observer->getData('custom_text');

        // Log the custom_text data
        $this->logger->info('Custom Text Data: ' . $observerData);

        return $this;
    }
}
