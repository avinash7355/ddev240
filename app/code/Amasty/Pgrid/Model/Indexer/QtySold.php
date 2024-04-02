<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Indexer;

use Amasty\Pgrid\Api\Data\QtySoldInterface;
use Amasty\Pgrid\Model\ConfigProvider;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;

class QtySold implements IndexerActionInterface
{
    public const INDEXER_ID = 'amasty_pgrid_qty_sold';
    public const BATCH_SIZE = 1000;

    /**
     * @var AdapterInterface
     */
    private $salesConnection;

    /**
     * @var AdapterInterface
     */
    private $defaultConnection;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        ResourceConnection $resource,
        ConfigProvider $configProvider
    ) {
        $this->salesConnection = $resource->getConnection('sales');
        $this->defaultConnection = $resource->getConnection();
        $this->resource = $resource;
        $this->configProvider = $configProvider;
    }

    /**
     * @return QtySold
     */
    public function executeFull()
    {
        return $this->doReindex();
    }

    /**
     * Its unable to calculate sum of already indexed qty sold data with
     * newly placed order items because of compatibility with Magento Split Database feature.
     */
    public function executeList(array $orderIds)
    {
        $productGridIndexTable = $this->getTable(QtySoldInterface::QTY_SOLD_TABLE);
        $salesSelect = $this->getSalesSelect();
        $salesSelect->where('sales_order.entity_id IN (?)', $orderIds);
        $salesSelect->forUpdate(true);
        $orderProductsData = $this->salesConnection->fetchPairs($salesSelect);

        if ($orderProductsData) {
            $indexedDataSelect = $this->defaultConnection->select();
            $indexedDataSelect->from(
                $productGridIndexTable,
                ['product_id', 'qty_sold']
            )->where('product_id IN (?)', array_keys($orderProductsData));

            $indexedProductData = $this->defaultConnection->fetchPairs($indexedDataSelect);
            $indexedProductData = array_map(
                function ($productId, $qtySold) use ($orderProductsData) {
                    return [
                        'product_id' => $productId,
                        'qty_sold' => $qtySold + ($orderProductsData[$productId] ?? 0)
                    ];
                },
                array_keys($indexedProductData),
                array_values($indexedProductData)
            );

            $this->defaultConnection->insertOnDuplicate($productGridIndexTable, $indexedProductData);
        }

        return $this;
    }

    /**
     * @param int $id
     * @return QtySold
     */
    public function executeRow($id)
    {
        return $this->executeList([$id]);
    }

    /**
     * @param string $tableName
     * @return string
     */
    private function getTable($tableName)
    {
        return $this->resource->getTableName($tableName);
    }

    /**
     * Add zero qty_sold index to products
     *
     * @param int|int[] $ids
     * @return $this
     */
    public function addEmptyIndexByProductIds($ids)
    {
        $rows = array_map(function ($productId) {
            return [
                'product_id' => $productId,
                'qty_sold' => 0
            ];
        }, is_array($ids) ? $ids : [$ids]);

        $this->defaultConnection->insertOnDuplicate($this->getTable(QtySoldInterface::QTY_SOLD_TABLE), $rows);
        return $this;
    }

    /**
     * @return $this
     */
    private function doReindex()
    {
        $productGridIndexTable = $this->getTable(QtySoldInterface::QTY_SOLD_TABLE);
        $salesSelect = $this->getSalesSelect();
        $salesProductData = $this->salesConnection->fetchAll($salesSelect);
        $salesProductIds = array_column($salesProductData, 'product_id');
        $salesProductData = array_merge($this->getRemainedProducts($salesProductIds), $salesProductData);
        $this->insertBatchedIndexData($productGridIndexTable, $salesProductData);

        return $this;
    }

    private function getSalesSelect()
    {
        $fromDate = $this->getDateFrom();
        $toDate = $this->getDateTo();

        if ($this->configProvider->isIncludeRefunded()) {
            $expression = 'SUM(order_item.qty_ordered)';
        } else {
            $expression = 'SUM(order_item.qty_ordered) - SUM(order_item.qty_refunded)';
        }

        $salesSelect = $this->salesConnection->select();
        $salesSelect->from(
            ['sales_order' => $this->getTable('sales_order')],
            [
                'product_id' => 'order_item.product_id',
                'qty_sold' => new \Zend_Db_Expr($expression)
            ]
        )->joinInner(
            ['order_item' => $this->getTable('sales_order_item')],
            'order_item.order_id = sales_order.entity_id',
            []
        )->group('order_item.product_id');
        $this->addOrderStatuses($salesSelect);

        if ($fromDate || $toDate) {
            if ($fromDate && $toDate) {
                $salesSelect->where(
                    sprintf('sales_order.created_at BETWEEN \'%s\' and \'%s\'', $fromDate, $toDate)
                );
            } elseif ($fromDate) {
                $salesSelect->where('sales_order.created_at >= ?', $fromDate);
            } else {
                $salesSelect->where('sales_order.created_at <= ?', $toDate);
            }
        }

        return $salesSelect;
    }

    /**
     * @param array $entityIds
     * @return array
     */
    private function getRemainedProducts(array $entityIds)
    {
        $select = $this->defaultConnection->select()->from(
            $this->getTable('catalog_product_entity'),
            [
                'product_id' => 'entity_id',
                'qty_sold' => new \Zend_Db_Expr('0')
            ]
        );

        if ($entityIds) {
            $select->where('entity_id NOT IN (?)', $entityIds);
        }

        return $this->defaultConnection->fetchAll($select);
    }

    /**
     * Use insertOnDuplicate method to ignore index duplicates
     *
     * @param string $table
     * @param array $indexData
     */
    private function insertBatchedIndexData(string $table, array $indexData)
    {
        $counter = 0;
        foreach ($indexData as $data) {
            $insertData[] = $data;
            if ($counter++ == self::BATCH_SIZE) {
                $this->defaultConnection->insertOnDuplicate($table, $insertData);
                $insertData = [];
                $counter = 0;
            }
        }
        if (!empty($insertData)) {
            $this->defaultConnection->insertOnDuplicate($table, $insertData);
        }
    }

    /**
     * @return string
     */
    private function getDateFrom()
    {
        return $this->appendTime(
            $this->configProvider->getQtySoldFrom()
        );
    }

    /**
     * @return string
     */
    private function getDateTo()
    {
        return $this->appendTime(
            $this->configProvider->getQtySoldTo(),
            true
        );
    }

    /**
     * @param Select $select
     * @return bool
     */
    private function addOrderStatuses(Select $select)
    {
        if ($statuses = $this->configProvider->getQtySoldOrderStatuses()) {
            $statuses = explode(',', $statuses);
            $select->where('sales_order.status IN(?)', $statuses);

            return true;
        }

        return false;
    }

    private function appendTime(string $date, bool $isEnd = false): string
    {
        if (!$date) {
            return '';
        }

        if ($isEnd) {
            $date .= ' 23:59:59';
        } else {
            $date .= ' 00:00:00';
        }

        return $date;
    }
}
