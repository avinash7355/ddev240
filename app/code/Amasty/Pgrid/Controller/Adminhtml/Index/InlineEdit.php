<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Controller\Adminhtml\Index;

use Amasty\Base\Model\Serializer;
use Amasty\Pgrid\Model\Inventory\SourceItemUpdater;
use Amasty\Pgrid\Model\Product\TierPriceHandler;
use Amasty\Pgrid\Ui\Component\Listing\Column\Availability;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Attribute\ScopeOverriddenValue;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Edit\WeightResolver;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\TypeTransitionManager;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\CatalogInventory\Model\Stock;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\UrlRewrite\Model\UrlPersistInterface;

class InlineEdit extends \Amasty\Pgrid\Controller\Adminhtml\Index
{
    public const CATALOG_PRODUCT_ENTITY_TYPE_ID = 4;
    public const CATEGORIES_KEY = 'category_ids';
    public const MESSAGES_GROUP_KEY = 'amasty_pgrid';
    public const STRING_ATTRIBUTE_TYPES = [
        'varchar',
        'text',
        'datetime'
    ];
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\View\Element\UiComponentFactory
     */
    protected $factory;

    /**
     * @var \Amasty\Pgrid\Helper\Data
     */
    protected $helper;

    /**
     * @var array
     */
    protected $skipAttributeUpdate = ['sku', 'category_ids', 'tier_price'];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Store\Model\Store\Interceptor
     */
    protected $store;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    private $attribute;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    private $entityAttribute;

    /**
     * @var ScopeOverriddenValue
     */
    private $scopeOverriddenValue;

    /**
     * @var array
     */
    private $canUseDefaultValue = [];

    /**
     * @var ProductUrlRewriteGenerator
     */
    private $productUrlRewriteGenerator;

    /**
     * @var UrlPersistInterface
     */
    private $urlPersist;

    /**
     * @var ProductUrlPathGenerator
     */
    private $productUrlPathGenerator;

    /**
     * @var \Amasty\Pgrid\Ui\Component\Listing\Attribute\Repository
     */
    private $attributeRepository;

    /**
     * @var CategoryLinkManagementInterface
     */
    private $categoryLinkManagement;

    /**
     * @var TypeTransitionManager
     */
    private $typeTransitionManager;

    /**
     * @var SourceItemUpdater
     */
    private $sourceItemUpdater;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var TierPriceHandler
     */
    private $tierPriceHandler;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Amasty\Pgrid\Ui\Component\Listing\Attribute\Repository $attributeRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Element\UiComponentFactory $factory,
        \Amasty\Pgrid\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $attribute,
        ScopeOverriddenValue $scopeOverriddenValue,
        \Magento\Eav\Model\Entity\Attribute $entityAttribute,
        ProductUrlRewriteGenerator $productUrlRewriteGenerator,
        UrlPersistInterface $urlPersist,
        ProductUrlPathGenerator $productUrlPathGenerator,
        CategoryLinkManagementInterface $categoryLinkManagement,
        TypeTransitionManager $typeTransitionManager,
        SourceItemUpdater $sourceItemUpdater,
        Serializer $serializer,
        TierPriceHandler $tierPriceHandler
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;

        $this->logger = $logger;
        $this->factory = $factory;
        $this->helper = $helper;

        $this->storeManager = $storeManager;
        $this->stockRegistry = $stockRegistry;

        parent::__construct($context);
        $this->attribute = $attribute;
        $this->entityAttribute = $entityAttribute;
        $this->scopeOverriddenValue = $scopeOverriddenValue;
        $this->productUrlRewriteGenerator = $productUrlRewriteGenerator;
        $this->urlPersist = $urlPersist;
        $this->productUrlPathGenerator = $productUrlPathGenerator;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->typeTransitionManager = $typeTransitionManager;
        $this->sourceItemUpdater = $sourceItemUpdater;
        $this->serializer = $serializer;
        $this->tierPriceHandler = $tierPriceHandler;
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $postItems = $this->getRequest()->getParam('amastyItems', []);
        $storeId = $this->getRequest()->getParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        $this->store = $this->storeManager->getStore($storeId);
        $this->storeManager->setCurrentStore($this->store);

        foreach ($postItems as $productId => $postData) {
            foreach ($postData as $key => $value) {
                if (in_array($key, $this->attribute->getAttributeCodesByFrontendType('textarea'))
                    && $this->entityAttribute
                        ->loadByCode(self::CATALOG_PRODUCT_ENTITY_TYPE_ID, $key)
                        ->getData('is_wysiwyg_enabled')
                ) {
                    if ($value) {
                        $description = str_replace("\n", '</p><p>', '<p>' . $value . '</p>');
                        $postData[$key] = $description;
                    }
                }
            }

            $product = $this->productRepository->getById($productId, true, $storeId);

            if ($product->getId()) {
                $this->prepareProductAttributesWithUseDefault($product, $storeId);
                $this->updateProduct($product, $postData);
                $this->processUrlRewrites($product);
                $this->saveProduct($product);
            }
        }

        return $resultJson->setData(
            [
                'messages' => $this->getErrorMessages(),
                'error'    => $this->isErrorExists(),
                'grid'     => $this->getGridData()
            ]
        );
    }

    protected function getGridData()
    {
        $grid = '';
        if (!$this->isErrorExists()) {
            $component = $this->factory->create($this->_request->getParam('namespace'));
            $this->prepareComponent($component);
            $grid = $this->serializer->unserialize($component->render());
        }

        return $grid;
    }

    protected function getAttributes()
    {
        if (!$this->attributes) {
            $this->attributes = [];
            foreach ($this->attributeRepository->getList() as $attribute) {
                $this->attributes[$attribute->getAttributeCode()] = $this->attributes;
            }
        }

        return $this->attributes;
    }

    protected function getNumeric($value)
    {
        $result = 0;
        $value = str_replace(' ', '', $value);
        $sumArgs = explode('+', $value);

        foreach ($sumArgs as $arg) {
            if (false !== strpos($arg, '-')) {
                $subArgs = explode('-', $arg);

                foreach ($subArgs as $key => $subArg) {
                    if (0 == $key) {
                        $result += (float)$subArg;
                    } else {
                        $result -= (float)$subArg;
                    }
                }
            } else {
                $result += (float)$arg;
            }
        }

        return $result;
    }

    protected function setData(ProductInterface $product, $key, $val)
    {
        if (is_array($this->getAttributes()) && in_array($key, array_keys($this->getAttributes()))) {
            if (is_array($val)
                && !in_array($key, [self::CATEGORIES_KEY, ProductAttributeInterface::CODE_TIER_PRICE])
            ) {
                $val = implode(',', $val);
            }

            if (!in_array($key, $this->skipAttributeUpdate)) {
                $product->addAttributeUpdate($key, $val, $this->store->getId());
            }

            $product->setData($key, $val);

            if ($key === ProductAttributeInterface::CODE_WEIGHT && $product->getWeight() > 0) {
                $product->setData(ProductAttributeInterface::CODE_HAS_WEIGHT, WeightResolver::HAS_WEIGHT);
                $this->typeTransitionManager->processProduct($product);
            }

            if ($key === ProductAttributeInterface::CODE_TIER_PRICE) {
                $val = !is_string($val) ? $val : [];
                $this->tierPriceHandler->setTierPrices($product, $val);
            }
        } elseif ($key == 'qty') {
            if ($product->getTypeId() == Configurable::TYPE_CODE
                || $product->getTypeId() == Type::TYPE_BUNDLE
                || $product->getTypeId() == Grouped::TYPE_CODE
            ) {
                $this->messageManager->addWarningMessage(
                    __("You can't change qty for the composite products"),
                    self::MESSAGES_GROUP_KEY
                );
                return;
            }

            $quantityAndStockStatus = $product->getData('quantity_and_stock_status');
            $quantityAndStockStatus[$key] = $this->getNumeric($val);

            if ($this->helper->getModuleConfig('modification/availability')) {
                $quantityAndStockStatus['is_in_stock'] = $quantityAndStockStatus[$key] > 0
                    ? Stock::STOCK_IN_STOCK
                    : Stock::STOCK_OUT_OF_STOCK;
            }

            $product->setData('quantity_and_stock_status', $quantityAndStockStatus);
        } elseif ($key == 'amasty_availability') {
            $stockData = $product->getData('stock_data');

            if ($val == Availability::DISABLE_MANAGE_STOCK) {
                $stockData['manage_stock'] = 0;
                $stockData['use_config_manage_stock'] = 0;
                $product->setStockData($stockData);
            } else {
                $stockData['manage_stock'] = 1;
                $stockData['is_in_stock'] = $val;
                $stockData['use_config_manage_stock'] = 0;
                $product->setStockData($stockData);
            }
        } elseif ($key == 'amasty_backorders') {
            $stockData = $product->getData('stock_data');
            $stockData['backorders'] = $val;
            $product->setData('stock_data', $stockData);
        } else {
            $product->setData($key, $val);
        }
    }

    protected function updateProduct(ProductInterface $product, array $data)
    {
        foreach ($data as $key => $val) {
            if ($product->getData($key) !== $val || $product->getData($key) === null) {
                $this->setData($product, $key, $val);
            }
        }
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     *
     * @throws \Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException
     */
    private function processUrlRewrites(\Magento\Catalog\Model\Product $product)
    {
        if ($product->dataHasChangedFor('url_key') || $product->dataHasChangedFor('visibility')) {
            $product->setData('save_rewrites_history', true);
            $product->unsUrlPath();
            $product->setUrlPath($this->productUrlPathGenerator->getUrlPath($product));
            $this->urlPersist->replace($this->productUrlRewriteGenerator->generate($product));
        }
    }

    protected function saveProduct(ProductInterface $product)
    {
        try {
            $product->setCanSaveCustomOptions(true);
            $product->save();

            if ($product->dataHasChangedFor(self::CATEGORIES_KEY)) {
                $this->categoryLinkManagement->assignProductToCategories(
                    $product->getSku(),
                    array_filter($product->getCategoryIds())
                );
            }

            if ($product->dataHasChangedFor(ProductAttributeInterface::CODE_SKU)) {
                $this->sourceItemUpdater->update(
                    $product->getOrigData(ProductAttributeInterface::CODE_SKU),
                    $product->getSku()
                );
            }
        } catch (\Magento\Framework\Exception\InputException $e) {
            $this->messageManager->addErrorMessage(
                $this->getErrorWithProductId($product, $e->getMessage()),
                self::MESSAGES_GROUP_KEY
            );
            $this->logger->critical($e);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                $this->getErrorWithProductId($product, $e->getMessage()),
                self::MESSAGES_GROUP_KEY
            );
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                $this->getErrorWithProductId($product, 'We can\'t save the product.'),
                self::MESSAGES_GROUP_KEY
            );
            $this->logger->critical($e);
        }
    }

    protected function getErrorWithProductId(ProductInterface $product, $errorText)
    {
        return '[Product ID: ' . $product->getId() . '] ' . __($errorText);
    }

    protected function getErrorMessages()
    {
        $messages = [];
        foreach ($this->messageManager->getMessages(false, self::MESSAGES_GROUP_KEY)->getItems() as $error) {
            $messages[] = $error->getText();
        }

        return $messages;
    }

    protected function isErrorExists()
    {
        return (bool)$this->messageManager->getMessages(true, self::MESSAGES_GROUP_KEY)->getCount();
    }

    protected function prepareComponent(\Magento\Framework\View\Element\UiComponentInterface $component)
    {
        foreach ($component->getChildComponents() as $child) {
            $this->prepareComponent($child);
        }
        $component->prepare();
    }

    /**
     * @param Attribute $attribute
     * @param Product $product
     *
     * @return bool
     */
    private function canUseDefaultValue(Attribute $attribute, Product $product)
    {
        $attributeCode = $attribute->getAttributeCode();

        if ($product->isLockedAttribute($attributeCode)) {
            return false;
        }

        if (!isset($this->canUseDefaultValue[$attributeCode])) {
            $this->canUseDefaultValue[$attributeCode] =
                (bool)($attribute->getScope() != ProductAttributeInterface::SCOPE_GLOBAL_TEXT);
        }

        return $this->canUseDefaultValue[$attributeCode];
    }

    /**
     * @param Product $product
     * @param string  $storeId
     */
    private function prepareProductAttributesWithUseDefault(Product $product, $storeId)
    {
        if ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
            foreach ($product->getAttributes() as $attribute) {
                $attributeCode = $attribute->getAttributeCode();

                if ($this->canUseDefaultValue($attribute, $product)) {
                    if ($product->getData($attributeCode)
                        && !$this->scopeOverriddenValue->containsValue(
                            ProductInterface::class,
                            $product,
                            $attributeCode,
                            $storeId
                        )
                    ) {
                        $attributeType = $attribute->getBackendType();
                        $attributeValue = null;

                        if (in_array($attributeType, self::STRING_ATTRIBUTE_TYPES)) {
                            $attributeValue = false;
                        }

                        $product->setData($attributeCode, $attributeValue);
                    } elseif ($product->getData($attributeCode) === null) {
                        $product->unsetData($attributeCode);
                    }
                }
            }
        }
    }
}
