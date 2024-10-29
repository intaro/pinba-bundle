<?php

namespace Intaro\PinbaBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;

final class Statement extends AbstractStatementMiddleware
{
    private string $databaseHost;
    private string $sql;
    private ?Stopwatch $stopwatch;

    public function __construct(
        StatementInterface $statement,
        string $databaseHost,
        string $sql,
        ?Stopwatch $stopwatch = null
    ) {
        parent::__construct($statement);

        $this->databaseHost = $databaseHost;
        $this->sql = $sql;
        $this->stopwatch = $stopwatch;
    }

    public function execute($params = null): ResultInterface
    {
        $stopwatchEvent = null;
        if (null !== $this->stopwatch) {
            $tags = [
                'server' => $this->databaseHost ?: ($_SERVER['HOSTNAME'] ?? ''),
            ];

            if (preg_match('/^\s*(\w+)\s/u', $this->sql, $matches)) {
                $tags['group'] = 'doctrine::' . strtolower($matches[1]);
            } else {
                $tags['group'] = 'doctrine::';
            }

            $stopwatchEvent = $this->stopwatch->start($tags);
        }

        try {
            return parent::execute($params);
        } finally {
            if ($stopwatchEvent) {
                $stopwatchEvent->stop();
            }
        }
    }
}
