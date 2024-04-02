<?php

namespace Brainvire\CustomSystem\Block\Sales\Order\Invoice;

use Magento\Framework\View\Element\Template;

class ExtraFee extends Template
{
    public function getExtraFee()
    {
        // Add your logic to get the extra fee value here
        return 10;
    }
}