<?php
declare(strict_types=1);

namespace Customer\ProxyExample\Model;

use Customer\ProxyExample\Model\Featured\FeaturedByCategory;
use Customer\ProxyExample\Model\Featured\FeaturedBySales;
use Customer\ProxyExample\Logger\CustomLogger;

class Featured
{
    protected $featuredByCategory;
    protected $featuredBySales;
    protected $logger;

    public function __construct(
        FeaturedByCategory $featuredByCategory,
        FeaturedBySales $featuredBySales,
        CustomLogger $logger // Inject the logger
    ) {
        $this->featuredByCategory = $featuredByCategory;
        $this->featuredBySales = $featuredBySales;
        $this->logger = $logger; // Store the logger instance
    }

    public function getFeatured(): array
    {
        if ($this->featuredByCategory->count() < 6) {
            // Log a message before returning data
            $this->logger->log('Getting featured products by sales');
            return $this->featuredBySales->getFeatured();
        }

        return $this->featuredByCategory->getFeatured();
    }
}
