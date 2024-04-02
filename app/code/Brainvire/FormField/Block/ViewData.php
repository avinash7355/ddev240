<?php
namespace Brainvire\FormField\Block;

use Magento\Framework\View\Element\Template;
use Brainvire\FormField\Model\ResourceModel\Field\CollectionFactory;

class ViewData extends Template
{
    protected $fieldCollectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $fieldCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->fieldCollectionFactory = $fieldCollectionFactory;
    }

    public function getFormData()
    {
     $collection = $this->fieldCollectionFactory->create()
            ->addFieldToSelect(['first_name', 'last_name', 'email'])
            ->setOrder('entity_id', 'desc') 
            ->setPageSize(10); 

        return $collection->getData();
    }
}
