<?php
namespace Brainvire\SampleGrid\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;

class Newpost extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
    
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Add New '));
        return $resultPage;
    }
}