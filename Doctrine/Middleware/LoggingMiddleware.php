<?php

namespace Intaro\PinbaBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Middleware;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;

class LoggingMiddleware implements Middleware
{
    private string $databaseHost;
    private ?Stopwatch $stopwatch;

    public function __construct(
        string $databaseHost,
        ?Stopwatch $stopwatch = null
    ) {
        $this->databaseHost = $databaseHost;
        $this->stopwatch = $stopwatch;
    }

    public function wrap(DriverInterface $driver): DriverInterface
    {
        return new Driver($driver, $this->databaseHost, $this->stopwatch);
    }
}
