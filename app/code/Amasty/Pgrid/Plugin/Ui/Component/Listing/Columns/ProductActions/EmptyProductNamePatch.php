<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Plugin\Ui\Component\Listing\Columns\ProductActions;

use Magento\Catalog\Ui\Component\Listing\Columns\ProductActions;

class EmptyProductNamePatch
{
    public function beforePrepareDataSource(ProductActions $subject, array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item['name'] = $item['name'] ?? '';
            }
        }

        return [$dataSource];
    }
}
