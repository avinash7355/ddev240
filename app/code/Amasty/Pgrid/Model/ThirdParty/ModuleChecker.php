<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\ThirdParty;

use Magento\Framework\Module\Manager;

class ModuleChecker
{
    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @var bool
     */
    private $isInventoryModulesEnabled;

    public function __construct(Manager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function isInventoryEnabled(): bool
    {
        if ($this->isInventoryModulesEnabled === null) {
            $moduleNames = [
                'Magento_InventoryApi',
                'Magento_InventoryCatalogAdminUi',
                'Magento_InventoryConfigurationApi',
                'Magento_InventoryCatalogApi',
                'Magento_Inventory'
            ];

            $isOneModuleDisabled = false;
            foreach ($moduleNames as $name) {
                if (!$this->moduleManager->isEnabled($name)) {
                    $isOneModuleDisabled = true;
                    break;
                }
            }
            $this->isInventoryModulesEnabled = !$isOneModuleDisabled;
        }

        return $this->isInventoryModulesEnabled;
    }
}
