<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Indexer;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Ddl\TriggerFactory;
use Magento\Framework\Mview\View\CollectionInterface;
use Magento\Framework\Mview\ViewInterface;
use Magento\Sales\Model\ResourceModel\Order;

class Subscription extends \Magento\Framework\Mview\View\Subscription
{
    public function __construct(
        ResourceConnection $resource,
        TriggerFactory $triggerFactory,
        CollectionInterface $viewCollection,
        ViewInterface $view,
        Order $order,
        $tableName,
        $columnName,
        array $ignoredUpdateColumns = []
    ) {
        parent::__construct(
            $resource,
            $triggerFactory,
            $viewCollection,
            $view,
            $tableName,
            $columnName,
            $ignoredUpdateColumns ? $ignoredUpdateColumns : null
        );

        $this->connection = $order->getConnection();
    }
}
