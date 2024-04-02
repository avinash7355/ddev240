<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Ui\DataProvider\Product;

use Amasty\Pgrid\Model\Config\Source\Thumbnail;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class AddThumbnailFilterToCollection implements AddFilterToCollectionInterface
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var CollectionFactory
     */
    private $attributeCollectionFactory;

    public function __construct(
        MetadataPool $metadataPool,
        CollectionFactory $attributeCollectionFactory
    ) {
        $this->metadataPool = $metadataPool;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (!isset($condition['eq'])) {
            return;
        }

        $linkField = $this->metadataPool->getMetadata(ProductInterface::class)->getLinkField();
        $isNotAddedFilter = (int)$condition['eq'] === Thumbnail::NOT_ADDED;
        $sqlCondition = $isNotAddedFilter ? '=' : '<>';
        $thumbnailAttributeId = $this->getThumbnailAttributeId();

        $where = "vc.value {$sqlCondition} 'no_selection'";

        if ($isNotAddedFilter) {
            $where .= ' OR vc.value is NULL';
        }

        $collection->getSelect()->joinLeft(
            ['vc' => $collection->getTable('catalog_product_entity_varchar')],
            "(e.{$linkField} = vc.{$linkField} AND vc.attribute_id = {$thumbnailAttributeId})",
            []
        )->where(
            $where
        );
    }

    protected function getThumbnailAttributeId(): int
    {
        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection->addFieldToSelect('attribute_id')
            ->addFieldToFilter('attribute_code', ['eq' => 'thumbnail']);

        return (int)$attributeCollection->getConnection()->fetchOne($attributeCollection->getSelect());
    }
}
