<?php

namespace Intaro\PinbaBundle\Cache;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;

trait RedisStopwatchTrait
{
    protected $stopwatch;
    protected $stopwatchAdditionalTags = [];
    protected $serverName;
    protected int $expireMethodArgumentsCount;

    public function addWatchedServer(
        $host,
        $port = 6379,
        $timeout = 5
    ): void {
        $this->serverName = $host . (6379 == $port ? '' : ':' . $port);

        $this->pconnect($host, $port, $timeout);

        $expireMethodReflection = new \ReflectionMethod(\Redis::class, 'expire');
        $this->expireMethodArgumentsCount = $expireMethodReflection->getNumberOfParameters();
        if ($this->expireMethodArgumentsCount < 2 || $this->expireMethodArgumentsCount > 3) {
            throw new \RuntimeException(
                'Redis::expire method has wrong number of arguments ' . $this->expireMethodArgumentsCount . ' instead of 2 or 3'
            );
        }
    }

    public function setStopwatch(Stopwatch $stopwatch): void
    {
        $this->stopwatch = $stopwatch;
    }

    public function setStopwatchTags(array $tags): void
    {
        $this->stopwatchAdditionalTags = $tags;
    }

    protected function getStopwatchEvent($methodName)
    {
        $tags = $this->stopwatchAdditionalTags;
        $tags['group'] = 'redis::' . $methodName;

        if ($this->serverName) {
            $tags['server'] = $this->serverName;
        }

        return $this->stopwatch->start($tags);
    }

    protected function callWithStopwatch($methodName, callable $callback)
    {
        if ($this->stopwatch) {
            $event = $this->getStopwatchEvent($methodName);
        }

        $result = $callback();

        if ($this->stopwatch) {
            $event->stop();
        }

        return $result;
    }
}
