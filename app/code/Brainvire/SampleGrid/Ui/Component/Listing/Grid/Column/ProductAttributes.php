<?php
namespace Brainvire\SampleGrid\Ui\Component\Listing\Grid\Column;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Catalog\Model\Product\Attribute\Repository as AttributeRepository;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
class ProductAttributes extends Column
{
    private $attributeRepository;
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        AttributeRepository $attributeRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->attributeRepository = $attributeRepository;
    }
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $attributeIds = explode(',', $item['attributes']); // Assuming attributes are stored as comma-separated IDs
                $attributeLabels = [];
                foreach ($attributeIds as $attributeId) {
                    $attribute = $this->attributeRepository->get($attributeId);
                    $attributeLabels[] = $attribute->getDefaultFrontendLabel();
                }
                $item[$this->getData('name')] = implode(', ', $attributeLabels);
            }
        }
        return $dataSource;
    }
}
