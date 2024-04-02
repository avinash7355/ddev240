<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brainvire\ContactForm\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Main contact form block
 *
 * @api
 * @since 100.0.2
 */
class Department extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Template\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(Template\Context $context, ScopeConfigInterface $scopeConfig, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('contact/index/post', ['_secure' => true]);
    }

    /**
     * Get department values from configuration
     *
     * @return string
     */
    public function getDepartmentValues()
    {
        // Adjust the configuration path accordingly
        $configPath = 'contact/email/department';
        return $this->scopeConfig->getValue($configPath);
    }
}
