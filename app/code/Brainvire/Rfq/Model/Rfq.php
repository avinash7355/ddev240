<?php

namespace Brainvire\Rfq\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\Rfq\Model\ResourceModel\Rfq as ResourceModel;

class Rfq extends AbstractModel
{
	protected function _construct()
	{
		$this->_init(ResourceModel::class);
	}
}

?>