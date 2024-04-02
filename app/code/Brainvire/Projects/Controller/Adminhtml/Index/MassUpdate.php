<?php
namespace Brainvire\Projects\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Brainvire\Projects\Model\ResourceModel\Projects\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class MassUpdate extends Action
{
    protected $collectionFactory;
    protected $filter;
    protected $dateTime;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        DateTime $dateTime
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->dateTime = $dateTime;
        parent::__construct($context);
    }

    public function execute()
    {
        $status = $this->getRequest()->getParam('status', 0); // Default status

        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            $count = 0;
            foreach ($collection as $model) {
                $model->setStatus($status);

                // Update the updated_at column with the current timestamp in GMT
                $currentTimestamp = $this->dateTime->gmtTimestamp();
                $model->setUpdatedAt($currentTimestamp);

                $model->save();
                $count++;
            }

            $this->messageManager->addSuccess(__('A total of %1 item(s) have been updated.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brainvire_Projects::update'); // Update ACL
    }
}
