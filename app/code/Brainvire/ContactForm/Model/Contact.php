<?php
namespace Brainvire\ContactForm\Model;

use Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Brainvire\ContactForm\Model\ResourceModel\Contact::class);
    }
}
