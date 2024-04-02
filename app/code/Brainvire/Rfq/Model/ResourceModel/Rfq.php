<?php 

namespace Brainvire\Rfq\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Rfq extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('brainvire_rfq','product_id');
	}
}

?>