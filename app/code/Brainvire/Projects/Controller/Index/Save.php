<?php
namespace Brainvire\Projects\Controller\Index;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Brainvire\Projects\Model\ResourceModel\Projects\CollectionFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends AbstractAccount
{
    protected $projectCollectionFactory;
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CollectionFactory $projectCollectionFactory,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->projectCollectionFactory = $projectCollectionFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $data = $this->getRequest()->getPostValue();
        if (!empty($data)) {
            try {
                $projectId = $data['entity_id'];
                $projectCollection = $this->projectCollectionFactory->create();
                $projectCollection->addFieldToFilter('entity_id', $projectId);

                if ($projectCollection->getSize() > 0) {
                    foreach ($projectCollection as $project) {
                        $project->setDescription($data['description']);
                        // $project->setCustomerId($data['customer_id']);
                        $project->setStatus($data['status']);
                        $project->save();
                    }

                $this->_objectManager->get('Magento\Framework\App\Cache\TypeListInterface')->cleanType('full_page');

                    $result->setData(['success' => true]);
                } else {
                    $result->setData(['error' => 'Project not found']);
                }
            } catch (\Exception $e) {
                $result->setData(['error' => $e->getMessage()]);
            }
        } else {
            $result->setData(['error' => 'Invalid data']);
        }

        return $result;
    }
}
