<?php
namespace Brainvire\FormField\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Brainvire\FormField\Model\FieldFactory;

class SaveData extends Action
{
    protected $jsonFactory;
    protected $fieldFactory;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        FieldFactory $fieldFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->fieldFactory = $fieldFactory;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!empty($data)) {
            try {
                $newData = $this->fieldFactory->create();
                $newData->setData($data);
                $newData->save();
                $message = 'Data saved successfully!';
                $success = true;
            } catch (\Exception $e) {
                $message = 'Error: ' . $e->getMessage();
                $success = false;
            }
        } else {
            $message = 'No data to save.';
            $success = false;
        }

        return $resultJson->setData(['success' => $success, 'message' => $message]);
    }
}
