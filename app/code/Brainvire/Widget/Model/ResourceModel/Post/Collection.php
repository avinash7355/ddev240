<?php
namespace Brainvire\Widget\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Brainvire\Widget\Model\Post', 'Brainvire\Widget\Model\ResourceModel\Post');
	}

}
