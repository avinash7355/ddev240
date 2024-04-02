<?php

namespace Brainvire\Projects\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Brainvire\Projects\Model\Projects;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var Sample
     */
    protected $project;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param Sample $sample
     * @param Session $adminsession
     */
    public function __construct(
        Action\Context $context,
        Projects $project,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->project = $project;
        $this->adminsession = $adminsession;
    }

    /**
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
        //       var_dump($data);
        //     exit();
        //    echo "<pre>";
        //    print_r($data);
        //    exit();
            $entity_id = $this->getRequest()->getParam('entity_id');
            if ($entity_id) {
                $this->project->load($entity_id);
            }

            $this->project->setData($data);

            try {
                $this->project->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->project->getEntityId(), '_current' => true]);
                    }
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}