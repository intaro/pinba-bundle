<?php
namespace Intaro\PinbaBundle\Cache;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;

class Redis extends \Redis
{
    protected $stopwatch;
    protected $stopwatchAdditionalTags = array();
    protected $serverName;

    public function addWatchedServer(
        $host,
        $port = 6379,
        $timeout = 5
    ) {
        $this->serverName = $host . ($port == 6379 ? '' : ':' . $port);

        $this->pconnect($host, $port, $timeout);
    }

    public function setStopwatch(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public function setStopwatchTags(array $tags)
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

    public function exists($key)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('exists');
        }

        $result = parent::exists($key);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function set($key, $var)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('set');
        }

        $result = parent::set($key, $var);

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

    public function delete($key)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('delete');
        }

        $result = parent::delete($key);

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

    public function sAdd($tag, $id)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('sAdd');
        }

        $result = parent::sAdd($tag, $id);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }
}
