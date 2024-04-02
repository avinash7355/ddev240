<?php
namespace Brainvire\Widget\Model;
class Post extends \Magento\Framework\Model\AbstractModel
{
	protected function _construct()
	{
		$this->_init('Brainvire\Widget\Model\ResourceModel\Post');
	}
	    public function updateStatus($entityId, $status)
    {
        $this->load($entityId);
        if ($this->getId()) {
            $this->setStatus($status);
            $this->save();
            return true;
        }
        return false;
    }
}