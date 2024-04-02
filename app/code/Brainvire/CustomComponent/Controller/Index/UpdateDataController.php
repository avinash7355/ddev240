<?php
namespace Brainvire\CustomComponent\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;

class UpdateDataController extends Action
{
    protected $jsonFactory;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $response = [];

        try {
            $postData = $this->getRequest()->getPostValue();
            // print_r($postData);
            // exit();
            if (!empty($postData['tasks'])) {
                // echo $postData['tasks'];
                // exit();
                // Loop through the received tasks and update their status
                foreach ($postData['tasks'] as $task) {
                    $taskId = $task['entity_id'];
                    $status = $task['status'];
                
                    $taskModel = $this->_objectManager->create('Brainvire\CustomComponent\Model\Custom');
                    $taskModel->load($taskId);
                    if ($taskModel->getId()) {
                        $taskModel->setStatus($status);
                        $taskModel->save();
                    }
                }
                   echo "<pre>";
        exit();

                $response['success'] = true;
                $response['message'] = __('Task status updated successfully');
            } else {
                $response['success'] = false;
                $response['message'] = __('No task data received');
            }
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = __('Error updating task status: ' . $e->getMessage());
        }

        return $result->setData($response);
    }
}
