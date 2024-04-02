<?php

namespace Brainvire\CustomComponent\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Custom extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('bv_tasks_component', 'entity_id');
    }


}


