<?php

namespace Intaro\PinbaBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;

final class Driver extends AbstractDriverMiddleware
{
    private string $databaseHost;
    private ?Stopwatch $stopwatch;

    public function __construct(
        DriverInterface $driver,
        string $databaseHost,
        ?Stopwatch $stopwatch
    ) {
        parent::__construct($driver);

        $this->databaseHost = $databaseHost;
        $this->stopwatch = $stopwatch;
    }

    public function connect(array $params): ConnectionInterface
    {
        $connection = parent::connect($params);

        return new Connection(
            $connection,
            $this->databaseHost,
            $this->stopwatch
        );
    }
}
