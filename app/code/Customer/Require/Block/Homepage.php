<?php
namespace Customer\Require\Block;

class Homepage extends \Magento\Framework\View\Element\Template
{
    public function getMessage()
    {
        $msg = "Hello World";
        return $msg;
    }
}