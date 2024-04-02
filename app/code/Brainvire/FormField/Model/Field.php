<?php

namespace Brainvire\FormField\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\FormField\Model\ResourceModel\Field as ResourceModel;

class Field extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(ResourceModel::class);
	}
}

?>