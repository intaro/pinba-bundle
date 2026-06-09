<?php

namespace Intaro\PinbaBundle\Cache;

require_once __DIR__ . '/RedisStopwatchTrait.php';

$redisVersion = phpversion('redis');
if (PHP_VERSION_ID >= 80000 && is_string($redisVersion) && version_compare($redisVersion, '6.0.0', '>=')) {
    require_once __DIR__ . '/RedisPhp8.php';
    class_alias(RedisPhp8::class, RedisBase::class);
} else {
    require_once __DIR__ . '/RedisLegacy.php';
    class_alias(RedisLegacy::class, RedisBase::class);
}

class Redis extends RedisBase
{
}
