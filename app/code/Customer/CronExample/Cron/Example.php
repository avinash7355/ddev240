<?php
declare(strict_types=1);

namespace Customer\CronExample\Cron;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Xml\Parser;
use Magento\Framework\App\State;
use Magento\Customer\Model\CustomerFactory; // Import CustomerFactory
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Example
{
    protected $filesystem;
    protected $parser;
    protected $state;
    protected $customerFactory; // Inject CustomerFactory
    protected $logger;

    public function __construct(
        Filesystem $filesystem,
        Parser $parser,
        State $state,
        CustomerFactory $customerFactory, // Inject CustomerFactory
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->parser = $parser;
        $this->state = $state;
        $this->customerFactory = $customerFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            // Log the start of the cron job
            $this->logger->info('Starting running your custom cron job');

            // Log the XML file path
            $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $xmlFilePath = $mediaDirectory->getAbsolutePath('import/avi.xml');
            $this->logger->info('XML File Path: ' . $xmlFilePath);

            // Load and parse the XML file
            $xmlData = $this->parser->load($xmlFilePath)->xmlToArray();
            $this->logger->info('Loading the XML file');

            // Extract customer data from the XML
            $customerData = $xmlData['order']['customer'];
            $this->logger->info('Loading');

            // Save customer data
            $this->saveCustomer($customerData);
            $this->logger->info('Saved');

            // Optionally, you can log additional information or messages here

            // Log the completion of the cron job
            $this->logger->info('Your custom cron job finished');

        } catch (LocalizedException $e) {
            // Handle any exceptions or errors here
            $this->logger->error('An error occurred during your custom cron job: ' . $e->getMessage());
        }
    }

    protected function saveCustomer($customerData)
    {
        $this->logger->info('Your custom cron job finished');
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId(1); // Replace with the appropriate website ID
        $customer->setEmail($customerData['email']);
        $customer->setFirstname($customerData['name']);
        // You can set a last name if needed
        $customer->setLastname("masfmas");
        $customer->save();
    }
}
