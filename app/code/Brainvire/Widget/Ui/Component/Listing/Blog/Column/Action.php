<?php
namespace Brainvire\Widget\Ui\Component\Listing\Blog\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
class Action extends Column
{
   
    const ROW_EDIT_URL = 'widget/index/edit';
    const ROW_DELETE_URL = 'widget/index/delete';
    protected $_urlBuilder;
    
    private $_editUrl;
    private $_deleteUrl;
   
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL,
        $deleteUrl = self::ROW_DELETE_URL
    ) 
    {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        $this->_deleteUrl = $deleteUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_editUrl, 
                            ['entity_id' => $item['entity_id']]
                        ),
                        'label' => __('Edit'),
                    ];
                      $item[$name]['delete'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_deleteUrl, 
                            ['entity_id' => $item['entity_id']]
                        ),
                        'label' => __('Delete'),
                    ];
                }
            }
        }
        return $dataSource;
    }
}