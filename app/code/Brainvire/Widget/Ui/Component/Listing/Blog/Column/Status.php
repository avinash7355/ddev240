<?php
namespace Brainvire\Widget\Ui\Component\Listing\Blog\Column;
use Magento\Ui\Component\Listing\Columns\Column;
class Status extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $status = isset($item['status']) ? (int) $item['status'] : 0;
                $item['status'] = $status ? _('Enable') : _('Disable');
            }
        }
        return $dataSource;
    }
}