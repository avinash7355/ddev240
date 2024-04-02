<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Block\Adminhtml\Product\Grid\TierPrice;

use Amasty\Pgrid\Model\Config\Source\TierPrice as AmTierPrice;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Price\Group\AbstractGroup;
use Magento\Catalog\Model\Config\Source\Product\Options\TierPrice;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Directory\Helper\Data;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer;

class Content extends AbstractGroup
{
    /**
     * @var string
     */
    protected $_template = 'Amasty_Pgrid::tier_prices.phtml';

    /**
     * @var TierPrice
     */
    private $tierPriceValueType;

    /**
     * @var Serializer\Json
     */
    private $serializer;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        Context $context,
        GroupRepositoryInterface $groupRepository,
        Data $directoryHelper,
        Manager $moduleManager,
        Registry $registry,
        Serializer\Json $serializer,
        GroupManagementInterface $groupManagement,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CurrencyInterface $localeCurrency,
        AmTierPrice $tierPriceValueType,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $groupRepository,
            $directoryHelper,
            $moduleManager,
            $registry,
            $groupManagement,
            $searchCriteriaBuilder,
            $localeCurrency,
            $data
        );
        $this->tierPriceValueType = $tierPriceValueType;
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
    }

    public function getAllGroupsId(): array
    {
        return [$this->_groupManagement->getAllCustomersGroup()->getId() => __('ALL GROUPS')];
    }

    public function getProduct(): ProductInterface
    {
        $productId = $this->getLayout()->getBlock('tier_prices')->getData('entity_id');

        return $this->productRepository->getById((int)$productId);
    }

    public function isScopeGlobal(): bool
    {
        return $this->getProduct()->getAttributes()[ProductAttributeInterface::CODE_TIER_PRICE]->isScopeGlobal();
    }

    public function getPriceValueTypesJson(): string
    {
        return $this->serializer->serialize($this->tierPriceValueType->toOptionArray());
    }

    public function getGroupsJson(): string
    {
        $allGroupId = $this->getAllGroupsId();
        $groups = array_replace_recursive($allGroupId, $this->getCustomerGroups());

        return $this->serializer->serialize($groups);
    }

    public function getWebsitesJson(): string
    {
        return $this->serializer->serialize($this::getWebsites());
    }

    /**
     * Retrieve all assigned to product websites.
     *
     * @return array
     */
    public function getWebsites(): array
    {
        if ($this->_websites !== null) {
            return $this->_websites;
        }

        $this->_websites = [
            0 => ['name' => __('All Websites'), 'currency' => $this->_directoryHelper->getBaseCurrencyCode()]
        ];

        if (!$this->isScopeGlobal()) {
            $websites = $this->_storeManager->getWebsites();
            $productWebsiteIds = $this->getProduct()->getWebsiteIds();
            foreach ($websites as $website) {
                if (!in_array($website->getId(), $productWebsiteIds)) {
                    continue;
                }
                $this->_websites[$website->getId()] = [
                    'name' => $website->getName(),
                    'currency' => $website->getBaseCurrencyCode()
                ];
            }
        }

        return $this->_websites;
    }

    public function getApplyToJson(): string
    {
        $element = $this->getElement();
        $applyTo = $element->hasEntityAttribute()
            ? $element->getEntityAttribute()->getApplyTo()
            : [];

        return $this->serializer->serialize($applyTo);
    }
}
