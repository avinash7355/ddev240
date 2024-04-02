<?php
namespace Customer\Event\Controller\Index;

class CustomObserverfile extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(	
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $this->_eventManager->dispatch('my_custom_observer', ['custom_text' => 'Hi Avinash this is our custom log']);
        $resultPage->getConfig()->getTitle()->prepend(__('Welcome to Custom Observer page'));
        return $resultPage;
    }
}
?>
