<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param LoggerInterface $logger    A LoggerInterface instance
     * @param Stopwatch       $stopwatch A Stopwatch instance
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
            $tags = array('category' => 'doctrine');

            if (preg_match('/^\s*(\w+)\s/u', $sql, $matches)) {
                $tags['operation'] = strtolower($matches[1]);
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
