<?php

namespace Brainvire\SampleGrid\Model;

use Magento\Framework\Model\AbstractModel;
use Brainvire\SampleGrid\Model\ResourceModel\Custom as ResourceModel;

class Custom extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}