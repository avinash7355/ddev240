<?php

namespace Brainvire\ContactForm\Plugin\Contact;

use Brainvire\ContactForm\Model\ContactFactory;
use Magento\Contact\Controller\Index\Post as BaseExistingClass;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;

class Post extends BaseExistingClass
{
    protected $ContactFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var MailInterface
     */
    private $mail;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param ConfigInterface $contactsConfig
     * @param MailInterface $mail
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        ContactFactory $contactFactory,
        Context $context,
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null
    ) {
        $this->contactFactory = $contactFactory;
        parent::__construct($context, $contactsConfig, $mail, $dataPersistor, $logger);
        $this->context = $context;
        $this->mail = $mail;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
        
    }

    public function aroundExecute(
        \Magento\Contact\Controller\Index\Post $subject,
        callable $proceed
    ) {

        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        try {
            $this->customSendEmail($this->validatedParams());
            // Get form data
            $postData = $this->getRequest()->getPostValue();

            // Save data to the custom table using the factory
            $contactusModel = $this->contactFactory->create();
            $contactusModel->setName($postData['name'])
                    ->setEmail($postData['email'])
                    ->setTelephone($postData['telephone'])
                    ->setDepartment($postData['department'])
                    ->setComment($postData['comment'])
                    ->save();
            // You can modify the result if needed
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->dataPersistor->clear('brainvire_contact_queries');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('brainvire_contact_queries', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
            $this->dataPersistor->set('brainvire_contact_queries', $this->getRequest()->getParams());
        }
        return $this->resultRedirectFactory->create()->setPath('contact/index');
    }

    public function customSendEmail($post)
    {
        $this->mail->send(
            $post['email'],
            [
                'data' => new DataObject($post),
                'created_at' => date("Y-m-d h:m:s", time()),
                'department' => $post['department'],
            ]
        );
    }

    /**
     * Method to validated params.
     *
     * @return array
     * @throws \Exception
     */
    private function validatedParams()
    {
        $request = $this->getRequest();

        if (trim($request->getParam('name', '')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (trim($request->getParam('comment', '')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (\strpos($request->getParam('email', ''), '@') === false) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }
        if (trim($request->getParam('hideit', '')) !== '') {
            // phpcs:ignore Magento2.Exceptions.DirectThrow
            throw new \Exception();
        }

        return $request->getParams();
    }
}