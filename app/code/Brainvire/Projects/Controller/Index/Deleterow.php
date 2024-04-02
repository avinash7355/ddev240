<?php
namespace Brainvire\Projects\Controller\Index;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Brainvire\Projects\Model\ProjectsFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\Session as CustomerSession;
class Deleterow extends Action
{
    protected $projectsFactory;
    protected $resultJsonFactory;
    protected $customerSession;
    public function __construct(
        Context $context,
        ProjectsFactory $projectsFactory,
        JsonFactory $resultJsonFactory,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->projectsFactory = $projectsFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
    }
    public function execute()
    {
        $projectId = $this->getRequest()->getParam('entity_id');
        $result = [];
        if ($projectId) {
            try {
                $project = $this->projectsFactory->create();
                $project->load($projectId);
                // Get the logged-in customer's ID from the session
                $loggedInCustomerId = $this->customerSession->getCustomerId();
                // Check if the project's customer ID matches the logged-in customer's ID
                if ($project->getCustomerId() == $loggedInCustomerId) {
                    $project->delete();
                    $result['success'] = true;
                    $result['message'] = __('Project has been deleted.');
                } else {
                    $result['success'] = false;
                    $result['message'] = __('You do not have permission to delete this project.');
                }
            } catch (\Exception $e) {
                $result['success'] = false;
                $result['message'] = __('An error occurred while deleting the project: %1', $e->getMessage());
            }
        } else {
            $result['success'] = false;
            $result['message'] = __('Project ID is missing.');
        }
        // Return JSON response
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
