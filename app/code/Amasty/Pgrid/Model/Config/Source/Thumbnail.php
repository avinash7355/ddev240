<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Thumbnail implements OptionSourceInterface
{
    public const NOT_ADDED = 0;
    public const ADDED = 1;

    public function toOptionArray(): array
    {
        $result = [];

        foreach ($this->toArray() as $value => $label) {
            $result[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $result;
    }

    public function toArray(): array
    {
        return [
            self::ADDED => __('Added'),
            self::NOT_ADDED => __('Not Added')
        ];
    }
}
