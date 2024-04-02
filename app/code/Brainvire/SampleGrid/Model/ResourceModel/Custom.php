<?php

namespace Brainvire\SampleGrid\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Custom extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('bv_uigrid_custom', 'id');
    }
}


