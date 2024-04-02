<?php
namespace Brainvire\Projects\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Status extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $status = $item['status'];
                $item['status'] = ($status == 1) ? __('Enable') : __('Disable');
            }
        }
        return $dataSource;
    }
}
