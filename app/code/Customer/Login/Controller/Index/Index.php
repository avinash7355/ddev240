<?php

namespace Customer\Login\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory; // Add this line

class Index extends Action
{
    protected $resultPageFactory;
    protected $jsonFactory; // Add this property

    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $jsonFactory) // Add JsonFactory to constructor
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonFactory = $jsonFactory; // Initialize JsonFactory
    }

    public function execute()
    {
       
        dd('my-/,.route/asd/index/index');

        $json = $this->jsonFactory->create(); // Create a JSON response object
        
       
    }
}

