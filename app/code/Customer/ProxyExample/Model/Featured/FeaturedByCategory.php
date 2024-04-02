<?php
declare(strict_types=1);

namespace Customer\ProxyExample\Model\Featured;
class FeaturedByCategory implements FeaturedInterface{
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
            'product 1',
            'product 2',
            'product 3',
            'product 4',
            'product 5',
            'product 6',
        ];
    }

}