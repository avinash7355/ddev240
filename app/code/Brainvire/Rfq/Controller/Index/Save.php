<?php
namespace Brainvire\Rfq\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Brainvire\Rfq\Model\RfqFactory;

class Save extends Action
{
    protected $resultPageFactory;
    protected $rfqFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RfqFactory $rfqFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->rfqFactory = $rfqFactory;
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();
        print_r($postData);

        if (!empty($postData)) {
            try {
                $rfq = $this->rfqFactory->create();
                $rfq->setData($postData);
                $rfq->save();

                $this->messageManager->addSuccessMessage(__('Form submitted successfully!'));

            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Failed to submit the form. Please try again.'));
            }
        }

        $resultPage = $this->resultPageFactory->create();

        return $resultPage;
    }
}
