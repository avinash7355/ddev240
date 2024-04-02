<?php
declare(strict_types=1);

namespace Customer\ProxyExample\ViewModel;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Customer\ProxyExample\Model\Featured;
class Example implements ArgumentInterface
{
    protected Featured $featured;
    public function __construct(Featured $featured)
    {
        $this->featured=$featured;
    }
    public function getFeatured():array
    {
        return $this->featured->getFeatured();
    }
}