<?php
namespace Brainvire\Widget\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Brainvire\Widget\Model\PostFactory; // Replace with your model class
use Magento\Framework\Controller\Result\JsonFactory;

class UpdateStatus extends Action
{
    protected $postFactory;
    protected $jsonResultFactory;

    public function __construct(
        Context $context,
        PostFactory $postFactory, // Inject your model here
        JsonFactory $jsonResultFactory
    ) {
        parent::__construct($context);
        $this->postFactory = $postFactory;
        $this->jsonResultFactory = $jsonResultFactory;
    }

    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        $requestData = $this->getRequest()->getParam('tasks', []);

        if (!empty($requestData)) {
            try {
                foreach ($requestData as $task) {
                    $entityId = $task['entity_id'];
                    $status = $task['status'];

                    // Load the task by ID
                    $taskModel = $this->postFactory->create()->load($entityId);
                    if ($taskModel->getId()) {
                        // Update the task status
                        $taskModel->setStatus($status);
                        $taskModel->save();
                    }
                    
                }
                return $result->setData(['success' => true]);
            } catch (\Exception $e) {
                return $result->setData(['success' => false, 'error' => $e->getMessage()]);
            }
        }

        return $result->setData(['success' => false, 'error' => 'No data received']);
    }
}