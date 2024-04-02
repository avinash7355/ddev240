<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Ui\Component\Listing\Attribute;

class Repository extends \Magento\Catalog\Ui\Component\Listing\Attribute\Repository
{
    protected function buildSearchCriteria()
    {
        return $this->searchCriteriaBuilder
            ->addFilter(
                'frontend_input',
                [
                    'textarea',
                    'text',
                    'weight',
                    'price',
                    'date',
                    'boolean',
                    'select',
                    'multiselect',
                    'media_image',
                    'datetime'
                ],
                'in'
            )->create();
    }
}
