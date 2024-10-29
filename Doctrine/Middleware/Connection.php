<?php

namespace Intaro\PinbaBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Intaro\PinbaBundle\Stopwatch\StopwatchEvent;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;

final class Connection extends AbstractConnectionMiddleware
{
    private string $databaseHost;
    private ?Stopwatch $stopwatch;

    public function __construct(
        ConnectionInterface $connection,
        string $databaseHost,
        ?Stopwatch $stopwatch
    ) {
        parent::__construct($connection);

        $this->databaseHost = $databaseHost;
        $this->stopwatch = $stopwatch;
    }

    public function prepare(string $sql): Statement
    {
        return new Statement(
            parent::prepare($sql),
            $this->databaseHost,
            $sql,
            $this->stopwatch,
        );
    }

    public function query(string $sql): Result
    {
        $stopwatchEvent = $this->getStopwatchEvent($sql);

        try {
            return parent::query($sql);
        } finally {
            if ($stopwatchEvent) {
                $stopwatchEvent->stop();
            }
        }
    }

    public function exec(string $sql): int
    {
        $stopwatchEvent = $this->getStopwatchEvent($sql);

        try {
            return parent::exec($sql);
        } finally {
            if ($stopwatchEvent) {
                $stopwatchEvent->stop();
            }
        }
    }

    private function getStopwatchEvent(string $sql): ?StopwatchEvent
    {
        if (null === $this->stopwatch) {
            return null;
        }

        $tags = [
            'server' => $this->databaseHost ?: ($_SERVER['HOSTNAME'] ?? ''),
        ];

        if (preg_match('/^\s*(\w+)\s/u', $sql, $matches)) {
            $tags['group'] = 'doctrine::' . strtolower($matches[1]);
        } else {
            $tags['group'] = 'doctrine::';
        }

        return $this->stopwatch->start($tags);
    }
}
