<?php

namespace Intaro\PinbaBundle\Cache;

class RedisLegacy extends \Redis
{
    use RedisStopwatchTrait;

    public function get($key)
    {
        return $this->callWithStopwatch('get', function () use ($key) {
            return parent::get($key);
        });
    }

    public function mGet(array $keys)
    {
        return $this->callWithStopwatch('mGet', function () use ($keys) {
            return parent::mGet($keys);
        });
    }

    public function exists($key, ...$otherKeys)
    {
        return $this->callWithStopwatch('exists', function () use ($key, $otherKeys) {
            return parent::exists($key, ...$otherKeys);
        });
    }

    public function set($key, $value, $options = null)
    {
        return $this->callWithStopwatch('set', function () use ($key, $value, $options) {
            return parent::set($key, $value, $options);
        });
    }

    public function setex($key, $expire, $value)
    {
        return $this->callWithStopwatch('setex', function () use ($key, $expire, $value) {
            return parent::setex($key, $expire, $value);
        });
    }

    public function mSetNx($keyValues)
    {
        return $this->callWithStopwatch('mSetNx', function () use ($keyValues) {
            return parent::mSetNx($keyValues);
        });
    }

    public function expire($key, $expire, $mode = null)
    {
        return $this->callWithStopwatch('expire', function () use ($key, $expire, $mode) {
            if (2 === $this->expireMethodArgumentsCount) {
                return parent::expire($key, $expire);
            }

            return parent::expire($key, $expire, $mode);
        });
    }

    public function exec()
    {
        return $this->callWithStopwatch('exec', function () {
            return parent::exec();
        });
    }

    public function delete($key, ...$otherKeys)
    {
        return $this->callWithStopwatch('delete', function () use ($key, $otherKeys) {
            return parent::delete($key, ...$otherKeys);
        });
    }

    public function sMembers($key)
    {
        return $this->callWithStopwatch('sMembers', function () use ($key) {
            return parent::sMembers($key);
        });
    }

    public function sAdd($key, $value, ...$otherValues)
    {
        return $this->callWithStopwatch('sAdd', function () use ($key, $value, $otherValues) {
            return parent::sAdd($key, $value, ...$otherValues);
        });
    }
}
