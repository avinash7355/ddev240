<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Setup\Patch\Data;

use Amasty\Pgrid\Model\ConfigProvider;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class ConvertQtySoldDate implements DataPatchInterface
{
    private const CONFIG_PATH_PREFIX = 'amasty_pgrid/';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    public function __construct(
        ConfigProvider $configProvider,
        WriterInterface $configWriter,
        TimezoneInterface $timezone
    ) {
        $this->configProvider = $configProvider;
        $this->timezone = $timezone;
        $this->configWriter = $configWriter;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $qtySoldFrom = $this->configProvider->getQtySoldFrom();
        $qtySoldTo = $this->configProvider->getQtySoldTo();

        if ($qtySoldFrom && !$this->isPhpFormat($qtySoldFrom)) {
            $this->configWriter->save(
                self::CONFIG_PATH_PREFIX . ConfigProvider::QTY_SOLD_FROM,
                $this->format($qtySoldFrom)
            );
        }

        if ($qtySoldTo && !$this->isPhpFormat($qtySoldTo)) {
            $this->configWriter->save(
                self::CONFIG_PATH_PREFIX . ConfigProvider::QTY_SOLD_TO,
                $this->format($qtySoldTo)
            );
        }
    }

    private function isPhpFormat(string $date): bool
    {
        return (bool)\DateTime::createFromFormat(DateTime::DATE_PHP_FORMAT, $date);
    }

    private function format(string $date): string
    {
        return $this->timezone->date($date)->format(DateTime::DATE_PHP_FORMAT);
    }
}
