<?php

namespace Brainvire\Projects\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\Projects\Model\ResourceModel\Projects as ResourceModel;

class Projects extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(ResourceModel::class);
	}
}

?>