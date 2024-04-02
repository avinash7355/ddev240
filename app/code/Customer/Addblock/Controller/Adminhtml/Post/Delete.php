<?php
namespace Customer\Addblock\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Customer\Addblock\Model\ResourceModel\Faq\CollectionFactory;
use Customer\Addblock\Model\FaqFactory;
use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $filter;
    protected $collectionFactory;
    protected $faqFactory;

    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        FaqFactory $faqFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->faqFactory = $faqFactory;
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $count = 0;

        try {
            foreach ($collection as $model) {
                $faqModel = $this->faqFactory->create();
                $faqModel->load($model->getId());
                $faqModel->delete();
                $count++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting: %1', $e->getMessage()));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/Post');
    }
}
