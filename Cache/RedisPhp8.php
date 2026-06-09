<?php

namespace Intaro\PinbaBundle\Cache;

class RedisPhp8 extends \Redis
{
    use RedisStopwatchTrait;

    public function get(string $key): mixed
    {
        return $this->callWithStopwatch('get', function () use ($key) {
            return parent::get($key);
        });
    }

    public function mGet(array $keys): \Redis|array|false
    {
        return $this->callWithStopwatch('mGet', function () use ($keys) {
            return parent::mGet($keys);
        });
    }

    public function exists(mixed $key, mixed ...$otherKeys): \Redis|int|bool
    {
        return $this->callWithStopwatch('exists', function () use ($key, $otherKeys) {
            return parent::exists($key, ...$otherKeys);
        });
    }

    public function set(string $key, mixed $value, mixed $options = null): \Redis|string|bool
    {
        return $this->callWithStopwatch('set', function () use ($key, $value, $options) {
            return parent::set($key, $value, $options);
        });
    }

    public function setex(string $key, int $expire, mixed $value)
    {
        return $this->callWithStopwatch('setex', function () use ($key, $expire, $value) {
            return parent::setex($key, $expire, $value);
        });
    }

    public function mSetNx(array $keyValues): \Redis|bool
    {
        return $this->callWithStopwatch('mSetNx', function () use ($keyValues) {
            return parent::mSetNx($keyValues);
        });
    }

    public function expire(string $key, int $expire, ?string $mode = null): \Redis|bool
    {
        return $this->callWithStopwatch('expire', function () use ($key, $expire, $mode) {
            if (2 === $this->expireMethodArgumentsCount) {
                return parent::expire($key, $expire);
            }

            return parent::expire($key, $expire, $mode);
        });
    }

    public function exec(): \Redis|array|false
    {
        return $this->callWithStopwatch('exec', function () {
            return parent::exec();
        });
    }

    public function delete(array|string $key, string ...$otherKeys): \Redis|int|false
    {
        return $this->callWithStopwatch('delete', function () use ($key, $otherKeys) {
            return parent::delete($key, ...$otherKeys);
        });
    }

    public function sMembers(string $key): \Redis|array|false
    {
        return $this->callWithStopwatch('sMembers', function () use ($key) {
            return parent::sMembers($key);
        });
    }

    public function sAdd(string $key, mixed $value, mixed ...$otherValues): \Redis|int|false
    {
        return $this->callWithStopwatch('sAdd', function () use ($key, $value, $otherValues) {
            return parent::sAdd($key, $value, ...$otherValues);
        });
    }
}
