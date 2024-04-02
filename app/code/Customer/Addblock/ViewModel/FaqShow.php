<?php

namespace Customer\Addblock\ViewModel;

use Customer\Addblock\Model\ResourceModel\faq\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;


class FaqShow implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    private $collection;
    private $scopeConfig;

    public function __construct(Collection $collection,ScopeConfigInterface $scopeConfig)
    {
        $this->collection = $collection;
         $this->scopeConfig = $scopeConfig;
    }

    public function getMaxItems()
    {
          return (int) $this->scopeConfig->getValue('faq/frontend_settings/visible_question_count',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getLimitedFaq(){
        $maxItems = $this->getMaxItems();
        return $this->collection->setPageSize($maxItems);
    }
}
?>