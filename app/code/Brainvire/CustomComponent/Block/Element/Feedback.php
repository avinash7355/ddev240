<?php

namespace Brainvire\CustomComponent\Block\Element;

use Magento\Framework\View\Element\Template;
use Brainvire\CustomComponent\Model\ResourceModel\Custom\CollectionFactory;
use Magento\Framework\Json\Helper\Data;

class Feedback extends Template
{
   protected $feedbackCollectionFactory;
   protected $jsonHelper;

   public function __construct(
       Template\Context $context,
       CollectionFactory $feedbackCollectionFactory,
       Data $jsonHelper,
       array $data = []
   ){
       $this->feedbackCollectionFactory = $feedbackCollectionFactory;
       $this->jsonHelper = $jsonHelper;
       parent::__construct($context, $data);
   }

   public function feedbackLastData(){
       // Get the last feedback
       $feedbackData = $this->feedbackCollectionFactory->create()
           ->getData();
       
       // Serialize the data using json_encode
       return $this->jsonHelper->jsonEncode($feedbackData);
   }
}
