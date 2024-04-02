<?php
namespace Brainvire\SampleGrid\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
class Delete extends Action
{

    protected $grid;
    
    public function __construct(
        Context $context,
        \Brainvire\SampleGrid\Model\Custom $grid
    ) {
        parent::__construct($context);
        $this->grid = $grid;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Brainvire_SampleGrid::delete');
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /* @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->grid;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}