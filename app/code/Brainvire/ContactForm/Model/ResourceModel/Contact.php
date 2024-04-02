<?php
namespace Brainvire\ContactForm\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Contact extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('brainvire_contact_queries', 'entity_id');
    }
}
