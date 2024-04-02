<?php

namespace Brainvire\CustomComponent\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\CustomComponent\Model\ResourceModel\Custom as ResourceModel;

class Custom extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
   // In Brainvire\CustomComponent\Model\Custom class
// public function updateData($entityId, $status)
// {
//     // Your update logic here
// }


}