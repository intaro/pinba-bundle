<?php

namespace Intaro\PinbaBundle\Logger;

use Doctrine\DBAL\Logging\SQLLogger;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;

/**
 * DbalLogger.
 */
class DbalLogger implements SQLLogger
{
    protected $stopwatch;
    protected $databaseHost;
    protected $stopwatchEvent;

    /**
     * Constructor.
     *
     * @param Stopwatch $stopwatch A Stopwatch instance
     */
    public function __construct(Stopwatch $stopwatch = null, $host = null)
    {
        $this->stopwatch = $stopwatch;
        $this->databaseHost = $host;
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null): void
    {
        if (null !== $this->stopwatch) {
            $tags = [
                'server' => $this->databaseHost ?: ($_SERVER['HOSTNAME'] ?? ''),
            ];

            if (preg_match('/^\s*(\w+)\s/u', $sql, $matches)) {
                $tags['group'] = 'doctrine::' . strtolower($matches[1]);
            } else {
                $tags['group'] = 'doctrine::';
            }

            $this->stopwatchEvent = $this->stopwatch->start($tags);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery(): void
    {
        if (null !== $this->stopwatchEvent) {
            $this->stopwatchEvent->stop();
            $this->stopwatchEvent = null;
        }
    }
}
