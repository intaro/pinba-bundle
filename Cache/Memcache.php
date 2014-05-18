<?php
namespace Intaro\PinbaBundle\Cache;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;

class Memcache extends \Memcache
{
    protected $stopwatch;

    public function setStopwatch(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public function getStopwatchEvent($methodName)
    {
        $v = explode('::', $methodName);
        if (sizeof($v) > 1) {
            $tags = array('group' => 'memcache::' . $v[1]);
        }

        return $this->stopwatch->start($tags);
    }

    public function add($key, $var, $flag = null, $expire = null)
    {
        if ($this->stopwatch) {
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
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
            $e = $this->getStopwatchEvent(__METHOD__);
        }

        $result = parent::tags_delete($tags);

        if ($this->stopwatch) {
            $e->stop();
        }

        return $result;
    }
}
