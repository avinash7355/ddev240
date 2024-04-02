<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Plugin\Catalog\Model\Product\Attribute\Source\Countryofmanufacture;

use Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture as MagentoCountryofmanufacture;
use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection;
use Magento\Framework\Data\Collection;
use Magento\Store\Model\Store;

/**
 * Sorting by 'country_of_manufacture' attribute is added.
 *
 * There is no sorting function in the attribute Source. Abstract sorting is used instead of.
 * @see \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource::addValueSortToCollection
 */
class Sorting
{
    public function afterAddValueSortToCollection(
        MagentoCountryofmanufacture $subject,
        MagentoCountryofmanufacture $result,
        AbstractCollection $collection,
        string $dir = Collection::SORT_ORDER_DESC
    ) {
        $attribute = $subject->getAttribute();
        $attributeCode = $attribute->getAttributeCode();
        $attributeId = $attribute->getId();
        $attributeTable = $attribute->getBackend()->getTable();
        $linkField = $attribute->getEntity()->getLinkField();

        $valueTable1 = $attributeCode . '_t1';
        $valueTable2 = $attributeCode . '_t2';
        $defaultStoreId = Store::DEFAULT_STORE_ID;

        $collection->getSelect()->joinLeft(
            [$valueTable1 => $attributeTable],
            "e.{$linkField}={$valueTable1}.{$linkField}" .
            " AND {$valueTable1}.attribute_id='{$attributeId}'" .
            " AND {$valueTable1}.store_id='{$defaultStoreId}'",
            []
        )->joinLeft(
            [$valueTable2 => $attributeTable],
            "e.{$linkField}={$valueTable2}.{$linkField}" .
            " AND {$valueTable2}.attribute_id='{$attributeId}'" .
            " AND {$valueTable2}.store_id='{$collection->getStoreId()}'",
            []
        );

        $valueExpr = $collection->getConnection()->getCheckSql(
            $valueTable2 . '.value_id > 0',
            $valueTable2 . '.value',
            $valueTable1 . '.value'
        );

        $collection->getSelect()->order(
            new \Zend_Db_Expr('ISNULL(' . $valueExpr . '), ' . $valueExpr . ' ' . $dir)
        );

        return $subject;
    }
}
