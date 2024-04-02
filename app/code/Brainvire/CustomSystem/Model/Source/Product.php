<?php

namespace Brainvire\CustomSystem\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class Product implements ArrayInterface
{

    protected $collectionFactory;

    /**
     * @param EavConfig $eavConfig
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->collectionFactory->create()->addAttributeToSelect('*');
        foreach ($arr as $key => $value) {
                $optionArray[] = [
                    'value' => $value->getId(),
                    'label' => $value->getName(),
                ];
        }
        return $optionArray;
    }
}