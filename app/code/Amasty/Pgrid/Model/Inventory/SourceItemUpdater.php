<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Inventory;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Message\ManagerInterface;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;
use Magento\InventoryCatalogApi\Model\SourceItemsProcessorInterface;

class SourceItemUpdater
{
    /**
     * @var ObjectFactory
     */
    private $msiObjectFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ObjectFactory $msiObjectFactory,
        ManagerInterface $messageManager
    ) {
        $this->msiObjectFactory = $msiObjectFactory;
        $this->messageManager = $messageManager;
    }

    public function update(string $origSku, string $sku): void
    {
        try {
            /** @var GetSourceItemsBySkuInterface $getSourceItemBySku */
            $getSourceItemBySku = $this->msiObjectFactory->createMsiObject(
                GetSourceItemsBySkuInterface::class
            );
            /** @var SourceItemsProcessorInterface $sourceItemsProcessor */
            $sourceItemsProcessor = $this->msiObjectFactory->createMsiObject(
                SourceItemsProcessorInterface::class
            );

            if ($getSourceItemBySku && $sourceItemsProcessor) {
                $items = [];
                foreach ($getSourceItemBySku->execute($origSku) as $item) {
                    $items[] = $item->getData();
                }
                $sourceItemsProcessor->execute($sku, $items);
            }
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while updating SKU'));
        }
    }
}
