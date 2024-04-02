<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model;

use Amasty\Base\Model\ConfigProviderAbstract;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ConfigProvider extends ConfigProviderAbstract
{
    public const QTY_SOLD_FROM = 'extra_columns/qty_sold_settings/qty_sold_from';
    public const QTY_SOLD_TO = 'extra_columns/qty_sold_settings/qty_sold_to';
    public const QTY_SOLD_ORDERS = 'extra_columns/qty_sold_settings/qty_sold_orders';
    public const INCLUDE_REFUNDED = 'extra_columns/qty_sold_settings/include_refunded';

    /**
     * @var string
     */
    protected $pathPrefix = 'amasty_pgrid/';

    public function getQtySoldFrom(): string
    {
        return (string)$this->getValue(self::QTY_SOLD_FROM);
    }

    public function getQtySoldTo(): string
    {
        return (string)$this->getValue(self::QTY_SOLD_TO);
    }

    public function getQtySoldOrderStatuses(): string
    {
        return (string)$this->getValue(self::QTY_SOLD_ORDERS);
    }

    public function isIncludeRefunded(): bool
    {
        return $this->isSetFlag(self::INCLUDE_REFUNDED);
    }
}
