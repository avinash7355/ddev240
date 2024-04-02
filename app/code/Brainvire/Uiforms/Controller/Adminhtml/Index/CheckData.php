<?php
namespace Brainvire\Uiforms\Controller\Adminhtml\Index;

use Magento\Framework\Filesystem;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Data\Customer;

class CheckData extends Action
{
    protected $messageManager;
    protected $filesystem;
    protected $request;
    protected $customerFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        Filesystem $filesystem,
        RequestInterface $request,
        CustomerFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->messageManager = $messageManager;
        $this->filesystem = $filesystem;
        $this->request = $request;
        $this->customerFactory = $customerFactory;
    }

    public function execute()
    {
        // Get the selected XML file
        $xmlFileName = $this->request->getParam('import_xml');

        if (empty($xmlFileName)) {
            $this->messageManager->addError('Please select an XML file.');
        } else {
            $mediaDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $xmlFilePath = $mediaDirectory->getAbsolutePath('import/' . $xmlFileName);

            // Load and parse the XML file
            $xmlData = simplexml_load_file($xmlFilePath);
            // var_dump($xmlData);
            // exit();

            if (!$xmlData) {
                $this->messageManager->addError('Failed to load XML file.');
            } else {
                $this->saveCustomers($xmlData);
                $this->messageManager->addSuccess('XML data import successful.');
            }
        }

        // Redirect to the same page
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }

    protected function saveCustomers($xmlData)
    {
        foreach ($xmlData->item as $item) {
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId(1); // Replace with the appropriate website ID
            $customer->setEmail((string)$item->email);
            $customer->setFirstname((string)$item->name);
            $customer->setLastname("masfmas"); // You can set a last name if needed
            $customer->save();
        }
    }
}
