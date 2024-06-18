<?php

namespace Intaro\PinbaBundle\Cache;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;

class Redis extends \Redis
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

        // для совместимости с Redis 5
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

    public function get($key)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('get');
        }

        $result = parent::get($key);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function mGet(array $keys)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('mGet');
        }

        $result = parent::mGet($keys);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function exists($key, ...$other_keys)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('exists');
        }

        $result = parent::exists($key, ...$other_keys);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function set($key, $var, $opts = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('set');
        }

        $result = parent::set($key, $var, $opts);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function setex($key, $var, $expire)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('setex');
        }

        $result = parent::setex($key, $var, $expire);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function mSetNx($v)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('mSetNx');
        }

        $result = parent::mSetNx($v);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function expire($key, $expire, $mode = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('expire');
        }

        if (2 === $this->expireMethodArgumentsCount) {
            $result = parent::expire($key, $expire);
        } else {
            $result = parent::expire($key, $expire, $mode);
        }

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function exec()
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('exec');
        }

        $result = parent::exec();

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function delete($key, ...$other_keys)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('delete');
        }

        $result = parent::delete($key, ...$other_keys);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function sMembers($key)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('sMembers');
        }

        $result = parent::sMembers($key);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function sAdd($tag, $id, ...$other_values)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('sAdd');
        }

        $result = parent::sAdd($tag, $id, ...$other_values);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }
}
