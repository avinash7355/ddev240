<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Config\Source;

use Amasty\Base\Model\Serializer;
use Amasty\Pgrid\Ui\DataProvider\Product\AddCategoryFilterToCollection;
use Magento\Catalog\Ui\Component\Product\Form\Categories\Options as CategoryOptions;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Categories implements OptionSourceInterface
{
    public const CATEGORY_OPTIONS_CACHE_ID = 'amasty_prgid_category_options';

    /**
     * @var CategoryOptions
     */
    private $categoryOptionProvider;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var array|null
     */
    private $categoryOptions = null;

    public function __construct(
        CategoryOptions $categoryOptionProvider,
        RequestInterface $request,
        CacheInterface $cache,
        Serializer $serializer
    ) {
        $this->categoryOptionProvider = $categoryOptionProvider;
        $this->request = $request;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    public function toOptionArray(): array
    {
        $categoryOptions = $this->toArray();
        $categoryOptions[] = [
            'value' => AddCategoryFilterToCollection::NO_CATEGORY_FILTER,
            'is_active' => '1',
            'label' => __('No Categories')->render(),
        ];

        return $categoryOptions;
    }

    public function toArray(): array
    {
        if (!$this->categoryOptions) {
            if ($categoryOptionsCache = $this->cache->load($this->getCategoryTreeCacheId())) {
                $this->categoryOptions = $this->serializer->unserialize($categoryOptionsCache);
            } else {
                $this->categoryOptions = $this->categoryOptionProvider->toOptionArray();
                $this->cache->save(
                    $this->serializer->serialize($this->categoryOptions),
                    $this->getCategoryTreeCacheId(),
                    [\Magento\Catalog\Model\Category::CACHE_TAG]
                );
            }
        }

        return $this->categoryOptions;
    }

    private function getCategoryTreeCacheId(): string
    {
        return self::CATEGORY_OPTIONS_CACHE_ID . $this->request->getParam('store', 0);
    }
}
