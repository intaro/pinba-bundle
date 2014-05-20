<?php
namespace Intaro\PinbaBundle\Cache;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;

class Memcache extends \Memcache
{
    protected $stopwatch;
    protected $stopwatchAdditionalTags = array();
    protected $serverName;

    public function addWatchedServer(
        $host,
        $port = 11211
    ) {
        $this->serverName = $host . ($port == 11211 ? '' : ':' . $port);

        $this->addServer($host, $port);
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
        $tags['category'] = 'memcache';
        $tags['group'] = 'memcache::' . $methodName;

        if ($this->serverName) {
            $tags['server'] = $this->serverName;
        }

        return $this->stopwatch->start($tags);
    }

    public function add($key, $var, $flag = null, $expire = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('add');
        }

        $result = parent::add($key, $var, $flag, $expire);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function set($key, $var, $flag = null, $expire = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('set');
        }

        $result = parent::set($key, $var, $flag, $expire);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function replace($key, $var, $flag = null, $expire = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('replace');
        }

        $result = parent::replace($key, $var, $flag, $expire);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function get($key, &$flags = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('get');
        }

        $result = parent::get($key, $flags);

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

    // methods for memcached-tags
    // --------------------------

    public function tag_add($tag, $key)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('tag_add');
        }

        $result = parent::tag_add($tag, $key);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function tag_delete($tag)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('tag_delete');
        }

        $result = parent::tag_delete($tag);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }

    public function tags_delete($tags)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent('tags_delete');
        }

        $result = parent::tags_delete($tags);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }
}
