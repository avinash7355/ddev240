<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */

namespace Amasty\Pgrid\Plugin\Ui\Model;

class Manager extends AbstractReader
{
    /**
     * Added settings for product grid
     *
     * @param \Magento\Ui\Model\Manager $subject
     * @param array                     $result
     *
     * @return array
     */
    public function afterGetData(
        \Magento\Ui\Model\Manager $subject,
        $result
    ) {
        echo "manager";die;
        if (isset($result['product_listing']['children'])) {
            $result['product_listing']['children'] = $this->addAmastySettings($result['product_listing']['children']);
        }

        return $result;
    }
}
