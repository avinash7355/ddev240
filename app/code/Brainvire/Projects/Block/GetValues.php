<?php
namespace Brainvire\Projects\Block;

use Magento\Framework\View\Element\Template;
use Brainvire\Projects\Model\ProjectsFactory;
use Magento\Framework\Registry;

class GetValues extends Template
{
    protected $projectFactory;
    protected $registry;

    public function __construct(
        Template\Context $context,
        ProjectsFactory $projectFactory,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->projectFactory = $projectFactory;
        $this->registry = $registry;
    }

    public function getProjectData()
    {
        $projectId = $this->getRequest()->getParam('id'); 
        if (!$projectId) {
            return null; 
        }

        $projectModel = $this->projectFactory->create();
        $projectModel->load($projectId);

        return $projectModel;
    }

}
