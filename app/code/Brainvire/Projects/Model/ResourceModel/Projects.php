<?php 

namespace Brainvire\Projects\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Projects extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('brainvire_projects','entity_id');
	}
}

?>