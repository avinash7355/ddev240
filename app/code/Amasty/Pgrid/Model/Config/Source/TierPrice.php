<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class TierPrice implements OptionSourceInterface
{
    public const VALUE_FIXED = 'fixed';
    public const VALUE_PERCENT = 'percent';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::VALUE_FIXED, 'label' => __('Fixed')],
            ['value' => self::VALUE_PERCENT, 'label' => __('Discount')],
        ];
    }
}
