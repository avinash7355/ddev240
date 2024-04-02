<?php
namespace Customer\Login\Block;

use Magento\Framework\View\Element\Template;

class CustomBlock extends Template
{
    // Add any custom methods or logic here

    /**
     * Get a simple message for the custom block.
     *
     * @return string
     */
    public function getCustomMessage()
    {
        return 'This is a new custom block!';
    }
}
