<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\ResourceModel;

use Magento\Catalog\Api\Data\CategoryAttributeInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Category\Attribute;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Store\Model\Store;

class CategoryPathResource
{
    public const KEY_PATH = 'path';

    public const KEY_PRODUCT_IDS = 'product_ids';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    public function __construct(
        ResourceConnection $resourceConnection,
        AttributeRepositoryInterface $attributeRepository,
        MetadataPool $metadataPool
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->attributeRepository = $attributeRepository;
        $this->metadataPool = $metadataPool;
    }

    /**
     * @param int[] $productIds
     * @return array [
     *     (int) => [
     *         'path' => [(int)], // Category path by IDs
     *         'product_ids' => [(int)]
     *     ], // key is category ID
     * ]
     */
    public function getProductCategoryPaths(array $productIds): array
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()->from(
            ['ent' => $this->resourceConnection->getTableName('catalog_category_entity')],
            ['entity_id', 'path']
        );
        $select->joinLeft(
            ['rel' => $this->resourceConnection->getTableName('catalog_category_product')],
            'rel.category_id = ent.entity_id',
            ['product_id']
        );
        $select->where('rel.product_id IN (?)', $productIds, \Zend_Db::INT_TYPE);

        $result = [];
        foreach ($connection->fetchAll($select) as $value) {
            $categoryId = (int) $value['entity_id'];

            $path = explode('/', $value['path']);
            $path = array_map('intval', $path);
            $path = array_filter($path, [$this, 'filterCategoryPathIds']);

            $result[$categoryId][self::KEY_PATH] = $path;
            $result[$categoryId][self::KEY_PRODUCT_IDS][] = $value['product_id'];
        }

        return $result;
    }

    private function filterCategoryPathIds(int $categoryId): bool
    {
        return $categoryId !== Category::TREE_ROOT_ID;
    }

    /**
     * @param int[] $categoryIds
     * @return array [
     *     (int) => (string), // key = category ID, value = category name
     * ]
     */
    public function getCategoriesNames(array $categoryIds): array
    {
        /** @var Attribute $nameAttribute */
        $nameAttribute = $this->attributeRepository->get(CategoryAttributeInterface::ENTITY_TYPE_CODE, 'name');
        $connection = $this->resourceConnection->getConnection();
        $metadata = $this->metadataPool->getMetadata(CategoryInterface::class);
        $linkField = $metadata->getLinkField();

        $select = $connection->select()->from(
            ['e' => $this->resourceConnection->getTableName('catalog_category_entity')],
            ['entity_id']
        );
        $select->joinLeft(
            ['val' => $nameAttribute->getBackendTable()],
            "e.{$linkField} = val.{$linkField}",
            ['name' => 'value']
        );
        $select->where('e.entity_id IN (?)', $categoryIds, \Zend_Db::INT_TYPE);
        $select->where('val.attribute_id = ?', $nameAttribute->getId(), \Zend_Db::INT_TYPE);
        $select->where('val.store_id = ?', Store::DEFAULT_STORE_ID);

        return $connection->fetchPairs($select);
    }
}
