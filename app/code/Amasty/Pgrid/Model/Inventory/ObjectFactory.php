<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Inventory;

use Amasty\Pgrid\Model\ThirdParty\ModuleChecker;
use Magento\Framework\ObjectManagerInterface;

class ObjectFactory
{
    /**
     * @var ModuleChecker
     */
    private $moduleChecker;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        ModuleChecker $moduleChecker,
        ObjectManagerInterface $objectManager
    ) {
        $this->moduleChecker = $moduleChecker;
        $this->objectManager = $objectManager;
    }

    public function createMsiObject(string $class): ?object
    {
        if (!$this->moduleChecker->isInventoryEnabled()) {
            return null;
        }

        return $this->objectManager->create($class);
    }
}
