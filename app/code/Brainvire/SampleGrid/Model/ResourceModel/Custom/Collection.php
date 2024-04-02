<?php
namespace Brainvire\SampleGrid\Model\ResourceModel\Custom;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Brainvire\SampleGrid\Model\Custom as Model;
use Brainvire\SampleGrid\Model\ResourceModel\Custom as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}