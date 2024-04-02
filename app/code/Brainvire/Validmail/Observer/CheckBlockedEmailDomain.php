<?php

namespace Brainvire\Validmail\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\CustomerFactory;

class CheckBlockedEmailDomain implements ObserverInterface
{
    protected $messageManager;
    protected $customerFactory;

    public function __construct(
        ManagerInterface $messageManager,
        CustomerFactory $customerFactory
    ) {
        $this->messageManager = $messageManager;
        $this->customerFactory = $customerFactory;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getCustomer();
        $email = $customer->getEmail();
        $blockedDomains = $this->getBlockedDomains();

        list($user, $domain) = explode('@', $email);
        
        if (in_array($domain, $blockedDomains)) {
           throw new \Magento\Framework\Exception\LocalizedException(__('You are not valid user'));
        }
    }

    protected function getBlockedDomains()
    {
        // Retrieve blocked domains from the configuration
        $blockedDomains = [];
        $configBlockedDomains = $this->scopeConfig->getValue('email_settings/general_settings/blocked_domains');
        if ($configBlockedDomains) {
            $blockedDomains = explode("\n", $configBlockedDomains);
        }
        return $blockedDomains;
    }
}
