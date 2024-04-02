<?php
namespace Brainvire\Widget\ViewModel;

use Brainvire\Widget\Model\ResourceModel\Post\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Widget implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    private $collection;
    private $scopeConfig;

    public function __construct(Collection $collection, ScopeConfigInterface $scopeConfig)
    {
        $this->collection = $collection;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get the data from the Post collection.
     *
     * @return \Brainvire\Widget\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        // You can apply any additional filters, sorting, or limits here.
        // Example: $this->collection->addFieldToFilter('status', 1);
        
        return $this->collection;
    }
}
