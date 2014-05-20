<?php

namespace Intaro\PinbaBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Doctrine\DBAL\Logging\SQLLogger;

/**
 * DbalLogger.
 *
 */
class DbalLogger implements SQLLogger
{
    protected $stopwatch;
    protected $stopwatchEvent;

    /**
     * Constructor.
     *
     * @param Stopwatch $stopwatch A Stopwatch instance
     */
    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        if (null !== $this->stopwatch) {
            $tags = array();

            if (preg_match('/^\s*(\w+)\s/u', $sql, $matches)) {
                $tags['group'] = 'doctrine::' . strtolower($matches[1]);
            }
            else {
                $tags['group'] = 'doctrine::';
            }

            $this->stopwatchEvent = $this->stopwatch->start($tags);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        if (null !== $this->stopwatchEvent) {
            $this->stopwatchEvent->stop();
            $this->stopwatchEvent = null;
        }
    }
}
