<?php

namespace Brainvire\Projects\Model;

use Brainvire\Projects\Model\ResourceModel\Projects\CollectionFactory;
use Magento\Framework\UrlInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    protected $urlBuilder;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $projectCollectionFactory,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $projectCollectionFactory->create();
        $this->urlBuilder = $urlBuilder;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        // echo "<pre>";
        // print_r($this->loadedData);
        // exit();
        $items = $this->collection->getItems();

        foreach ($items as $sample) {
            $sampleData = $sample->getData();

            $this->loadedData[$sample->getEntityId()] = $sampleData;
        }
        // echo "<pre>";
        // print_r($this->loadedData);
        // exit();
        return $this->loadedData;
    }
}
