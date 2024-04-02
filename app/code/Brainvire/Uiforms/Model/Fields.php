<?php

namespace Brainvire\Uiforms\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\Uiforms\Model\ResourceModel\Fields as ResourceModel;

class Fields extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(ResourceModel::class);
	}
}

?>