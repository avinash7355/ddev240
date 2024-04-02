<?php
declare(strict_types=1);

namespace Customer\ProxyExample\Model\Featured;

interface FeaturedInterface {
    public function getFeatured(): array;
    public function count(): int;
}
