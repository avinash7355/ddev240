<?php
declare(strict_types=1);

namespace Customer\ProxyExample\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CustomLogger
{
    protected $logger;

    public function __construct($name = 'custom_logger')
    {
        $this->logger = new Logger($name);
        $this->logger->pushHandler(new StreamHandler('var/log/custom.log'));
    }

    public function log($message)
    {
        $this->logger->info($message);
    }
}
