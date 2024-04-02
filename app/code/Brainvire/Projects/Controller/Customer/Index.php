<?php

namespace Brainvire\Projects\Controller\Customer;

class Index extends \Magento\Customer\Controller\AbstractAccount 
{
    public function execute() 
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}