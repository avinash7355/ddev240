<?php

namespace Brainvire\Projects\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    protected $project;

    public function __construct(
        Context $context,
        \Brainvire\Projects\Model\Projects $project
    ) {
        parent::__construct($context);
        $this->project = $project;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->project;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Data deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        $this->messageManager->addError(__('Data does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}