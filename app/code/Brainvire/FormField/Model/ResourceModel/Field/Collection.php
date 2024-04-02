<?php 

namespace Brainvire\FormField\Model\ResourceModel\Field;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Brainvire\FormField\Model\Field as Model;
use Brainvire\FormField\Model\ResourceModel\Field as ResourceModel;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(Model::class, ResourceModel::class);
	}
}

?>