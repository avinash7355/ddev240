<?php 

namespace Brainvire\Uiforms\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Fields extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('dummy_uiform','entity_id');
	}
}

?>