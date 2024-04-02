<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class SpecialChars extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item[$fieldName])) {
                    $item[$fieldName] = htmlspecialchars_decode($item[$fieldName]);
                }
            }
        }

        return $dataSource;
    }
}
