<?php
namespace Brainvire\ContactForm\Model\ResourceModel\Contact;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'brainvire_contact_data_collection';

    protected function _construct()
    {
        $this->_init(
            \Brainvire\ContactForm\Model\Contact::class,
            \Brainvire\ContactForm\Model\ResourceModel\Contact::class
        );
    }
}
