<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Model\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory;
use Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory;
use Magento\Catalog\Model\Config\Source\ProductPriceOptionsInterface;
use Magento\Catalog\Model\Product\TierPrice;
use Magento\Framework\Escaper;

class TierPriceHandler
{
    /**
     * @var ProductTierPriceInterfaceFactory
     */
    private $productTierPriceInterfaceFactory;

    /**
     * @var ProductTierPriceExtensionFactory
     */
    private $productTierPriceExtensionFactory;

    /**
     * @var Escaper
     */
    private $escaper;

    public function __construct(
        ProductTierPriceInterfaceFactory $productTierPriceInterfaceFactory,
        ProductTierPriceExtensionFactory $productTierPriceExtensionFactory,
        Escaper $escaper
    ) {
        $this->productTierPriceInterfaceFactory = $productTierPriceInterfaceFactory;
        $this->productTierPriceExtensionFactory = $productTierPriceExtensionFactory;
        $this->escaper = $escaper;
    }

    /**
     * @param ProductInterface $product
     * @param array $tierPriceDataArray [
     * 'tierPrice'=>[
     * 'website_id'=>'website Id', 'cust_group'=>'customer Group', 'price_qty'=>'price Qty',
     * 'value_type'=>'fixed/percent', 'price'=>'price or discount'
     * ],
     * 'otherTierPrice'=>[...]
     * ]
     *
     * @return void
     */
    public function setTierPrices(ProductInterface $product, array $tierPriceDataArray): void
    {
        $product->setTierPrices($this->prepareDataToSet($tierPriceDataArray));
    }

    /**
     * @param TierPrice[] $productTierPrices
     *
     * @return array
     */
    public function prepareTierPricesToLoad(array $productTierPrices): array
    {
        $tierPrices = [
            'tierPriceHtml' => '',
            'tierPriceModal' => []
        ];

        foreach ($productTierPrices as $tierPriceItem) {
            if ((float)$tierPriceItem['qty'] !== 0.0 && (float)$tierPriceItem['value'] !== 0.0) {
                $tierPrices['tierPriceHtml'] .= $this->getTierPriceHtml($tierPriceItem);
                $tierPrices['tierPriceModal'][] = $this->getTierPriceData($tierPriceItem);
            }
        }

        return $tierPrices;
    }

    /**
     * @param array $tierPriceDataArray [
     * 'tierPrice'=>[
     * 'website_id'=>'website Id', 'cust_group'=>'customer Group', 'price_qty'=>'price Qty',
     * 'value_type'=>'fixed/percent', 'price'=>'price or discount'
     * ],
     * 'otherTierPrice'=>[...]
     * ]
     *
     * @return array
     */
    private function prepareDataToSet(array $tierPriceDataArray): array
    {
        $result = [];

        foreach ($tierPriceDataArray as $item) {
            if (empty($item['price_qty'])) {
                continue;
            }

            $tierPriceExtensionAttribute = $this->productTierPriceExtensionFactory->create()
                ->setWebsiteId($item['website_id']);

            if ($isPercentValue = $item['value_type'] === ProductPriceOptionsInterface::VALUE_PERCENT) {
                $tierPriceExtensionAttribute->setPercentageValue($item['price']);
            }

            $key = implode(
                '-',
                [$item['website_id'], $item['cust_group'], (int)$item['price_qty']]
            );
            $result[$key] = $this->productTierPriceInterfaceFactory
                ->create()
                ->setCustomerGroupId($item['cust_group'])
                ->setQty($item['price_qty'])
                ->setValue(!$isPercentValue ? $item['price'] : '')
                ->setExtensionAttributes($tierPriceExtensionAttribute);
        }

        return array_values($result);
    }

    private function getTierPriceHtml(TierPrice $tierPriceItem): string
    {
        return '<p style="width:130px;">' .
            $this->escaper->escapeHtml(__('For Qty')) . ' = ' . round((float)$tierPriceItem['qty'], 2) .
            $this->escaper->escapeHtml(__(' Price')) . ' = ' . round((float)$tierPriceItem['value'], 2)
            . '</p>';
    }

    private function getTierPriceData(TierPrice $tierPriceItem): array
    {
        $percentageValue = false;
        if ($tierPriceItem->getExtensionAttributes()
            && $tierPriceItem->getExtensionAttributes()->getPercentageValue()
        ) {
            $percentageValue = $tierPriceItem->getExtensionAttributes()->getPercentageValue();
        }

        return [
            'website_id' => $tierPriceItem->getExtensionAttributes()
                ? $tierPriceItem->getExtensionAttributes()->getWebsiteId()
                : 0,
            'cust_group' => $tierPriceItem->getCustomerGroupId(),
            'price_qty' => round((float)$tierPriceItem->getQty(), 2),
            'value_type' => $percentageValue ? 'percent' : 'fixed',
            'price' => $percentageValue ?: round((float)$tierPriceItem->getValue(), 2)
        ];
    }
}
