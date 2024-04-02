<?php 

namespace Brainvire\Projects\Model\ResourceModel\Projects;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Brainvire\Projects\Model\Projects as Model;
use Brainvire\Projects\Model\ResourceModel\Projects as ResourceModel;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(Model::class, ResourceModel::class);
	}


}

?>