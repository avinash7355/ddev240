<?php
namespace Brainvire\Widget\Controller\Adminhtml\Index;
class Savewidget extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;
	protected $PostFactory;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Brainvire\Widget\Model\PostFactory $PostFactory
	)
	{
		$this->PostFactory = $PostFactory;
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}
	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
      /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
	     try{
	     /** @var \Magento\Cms\Model\Page $model */
	           $model = $this->PostFactory->create();
			   $model->setData($data);
			   $model->save();
		    	$this->messageManager->addSuccessMessage(__('Saved Task.'));
			}catch(\Exception $e){
				 $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
			}
	 return $resultRedirect->setPath('*/*/');
	}

}