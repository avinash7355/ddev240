<?php declare(strict_types=1);
namespace Brainvire\Addimage\Model\Sales\Pdf;
use Magento\Sales\Model\Order\Pdf\Total\DefaultTotal;
class CustomField extends DefaultTotal
{
    public function getTotalsForDisplay(): array
    {
        $extraFee = 100.00;
        if ($extraFee === null) {
            return [];  
        }
        $amountInclTax = $this->getOrder()->formatPriceTxt($extraFee);
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        return [
            [
                'amount' => $this->getAmountPrefix() . $amountInclTax,
                'label' => __("Product image") . ':',
                'font_size' => $fontSize,
            ]
        ];
    }
}
