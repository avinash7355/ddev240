<?php
namespace Brainvire\Projects\Block;

use Magento\Framework\View\Element\Template;
use Brainvire\Projects\Model\ResourceModel\Projects\CollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;

class ProjectList extends Template
{
    protected $projectCollectionFactory;
    protected $customerSession;

    protected $pageSize = 5; // Set the number of items per page

    public function __construct(
        Template\Context $context,
        CollectionFactory $projectCollectionFactory,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->projectCollectionFactory = $projectCollectionFactory;
        $this->customerSession = $customerSession;
    }

    public function getProjects()
    {
        $page = $this->getRequest()->getParam('page', 10); // Get the current page from the request parameters
        $collection = $this->projectCollectionFactory->create();
        $collection->setPageSize($this->pageSize);
        $collection->setCurPage($page);
        return $collection->getItems();
    }

    public function getCustomerEntityId()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomerId();
        }

        return null;
    }

    public function getTotalPages()
    {
        $collection = $this->projectCollectionFactory->create();
        $totalItems = $collection->getSize();
        return ceil($totalItems / $this->pageSize);
    }
}