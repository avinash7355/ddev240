<?php 

namespace Brainvire\FormField\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Field extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('brainvire_form_field','entity_id');
	}
}

?>