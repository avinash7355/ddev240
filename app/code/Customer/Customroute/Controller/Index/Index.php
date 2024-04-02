<?php
namespace Customer\Customroute\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;

class Index extends Action
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
    }

    public function execute()
    {
        $result = $this->resultRawFactory->create();

        // Load and render your custom PHTML file
        $blockContent = $this->_view->getLayout()
            ->createBlock(\Magento\Framework\View\Element\Template::class)
            ->setTemplate('Customer_Customroute::blank_page.phtml')
            ->toHtml();

        $result->setContents($blockContent);
        return $result;
    }
}
