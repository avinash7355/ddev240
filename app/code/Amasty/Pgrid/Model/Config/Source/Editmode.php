<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) Amasty (https://www.amasty.com)
 * @package Extended Product Grid with Editor for Magento 2
 */
namespace Amasty\Pgrid\Model\Config\Source;

class Editmode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'single', 'label' => __('Single Cell')], ['value' => 'multi', 'label' => __('Multi Cell')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['single' => __('Single Cell'), 'multi' => __('Multi Cell')];
    }
}
