<?php 

namespace Brainvire\Uiforms\Model\ResourceModel\Fields;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Brainvire\Uiforms\Model\Fields as Model;
use Brainvire\Uiforms\Model\ResourceModel\Fields as ResourceModel;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(Model::class, ResourceModel::class);
	}


}

?>