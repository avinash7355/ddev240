<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Product;

use Amasty\Pgrid\Model\ResourceModel\CategoryPathResource;
use Magento\Framework\Escaper;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

class CategoryPathResolver
{
    /**
     * @var CategoryPathResource
     */
    private $categoryPathResource;

    /**
     * @var Escaper
     */
    private $escaper;

    public function __construct(
        CategoryPathResource $categoryPathResource,
        Escaper $escaper
    ) {
        $this->categoryPathResource = $categoryPathResource;
        $this->escaper = $escaper;
    }

    /**
     * Build array of product categories where path uses category names
     *
     * @param int[] $productsIds
     * @param Store $store
     * @return array [
     *     (int) => [
     *         (int) => [
     *             'id' => (string), // Category ID
     *             'name' => (string),
     *             'path' => (string) // Category path by names
     *         ], // key = Category ID
     *         []
     *     ], // key = Product ID
     *     []
     * ]
     */
    public function getProductCategories(array $productsIds, StoreInterface $store): array
    {
        $categoryPaths = $this->categoryPathResource->getProductCategoryPaths($productsIds);

        $rootCategoryId = (int) $store->getRootCategoryId();
        $this->filterPath($categoryPaths, $rootCategoryId);

        $categoryIds = $this->collectCategoryIds($categoryPaths);

        $categoriesNamesMap = $this->categoryPathResource->getCategoriesNames($categoryIds);

        $productCategories = [];
        foreach ($categoryPaths as $categoryId => $categoryPath) {
            $pathNames = [];
            foreach ($categoryPath[CategoryPathResource::KEY_PATH] as $categoryPathId) {
                $pathNames[] = $categoriesNamesMap[$categoryPathId] ?? '';
            }
            $pathData = [
                'id' => (string) $categoryId,
                'name' => $categoriesNamesMap[$categoryId] ?? '',
                'path' => $this->escaper->escapeHtml(implode('/', $pathNames)),
            ];

            foreach ($categoryPath[CategoryPathResource::KEY_PRODUCT_IDS] as $productId) {
                $productCategories[$productId][] = $pathData;
            }
        }

        return $productCategories;
    }

    private function filterPath(array &$categoryPaths, int $rootCategoryId): void
    {
        foreach ($categoryPaths as &$categoryPath) {
            $path = [];
            foreach (array_reverse($categoryPath[CategoryPathResource::KEY_PATH]) as $pathId) {
                if ($rootCategoryId === $pathId) {
                    break;
                }
                $path[] = $pathId;
            }

            $categoryPath[CategoryPathResource::KEY_PATH] = array_reverse($path);
        }
    }

    private function collectCategoryIds(array $categoryPaths): array
    {
        $categoryIds = array_keys($categoryPaths);
        foreach ($categoryPaths as $categoryPath) {
            foreach ($categoryPath[CategoryPathResource::KEY_PATH] as $pathId) {
                if (!in_array($pathId, $categoryIds, true)) {
                    $categoryIds[] = $pathId;
                }
            }
        }

        return $categoryIds;
    }
}
