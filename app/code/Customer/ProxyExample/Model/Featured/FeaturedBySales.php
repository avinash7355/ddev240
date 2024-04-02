<?php
declare(strict_types=1);

namespace Customer\ProxyExample\Model\Featured;
class FeaturedBySales implements FeaturedInterface{
    protected array $Featured=[];
    public function __construct()
    {
        $this->loadFeatured();
    }
    public function getFeatured():array
    {
        return $this->featured;
    }
    public function count():int 
    {
        return count($this->featured);
    }
    protected function loadFeatured(): void
    {
        $this->featured =[
            'sales 1',
            'sales 2',
            'sales 3',
            'sales 4',
            'sales 5',
            'sales 6',
        ];
    }

}