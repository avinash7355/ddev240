<?php
declare(strict_types=1);

namespace Training\vmexample\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Dmessage implements ArgumentInterface
{
    public function getMessage(): string
    {
        return "asdad adassadfa fasasdf";
    }
}
