<?php
declare(strict_types=1);

namespace Brainvire\CustomComponent\Controller\Index;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{

    private $pageFactory;
    private $request;
    public function __construct(PageFactory $pageFactory, RequestInterface $request)
    {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }
    
    public function execute()
    {
        return $this->pageFactory->create();
    }
}

