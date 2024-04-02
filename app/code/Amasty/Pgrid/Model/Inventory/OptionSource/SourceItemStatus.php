<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Inventory\OptionSource;

use Amasty\Pgrid\Model\Inventory\ObjectFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\InventoryCatalogAdminUi\Model\OptionSource\SourceItemStatus as InventorySourceItemStatus;

class SourceItemStatus implements OptionSourceInterface
{
    /**
     * @var ObjectFactory
     */
    private $msiObjectFactory;

    public function __construct(
        ObjectFactory $msiObjectFactory
    ) {
        $this->msiObjectFactory = $msiObjectFactory;
    }

    public function toOptionArray(): array
    {
        /** @var  InventorySourceItemStatus $sourceItemStatusObject */
        $sourceItemStatusObject = $this->msiObjectFactory->createMsiObject(
            InventorySourceItemStatus::class
        );

        return $sourceItemStatusObject ? $sourceItemStatusObject->toOptionArray() : [];
    }
}
