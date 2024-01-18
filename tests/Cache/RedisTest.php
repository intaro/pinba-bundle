<?php

namespace Intaro\PinbaBundle\Tests\Cache;

use Intaro\PinbaBundle\Cache\Redis;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     *
     * Check that Pinba Redis class is compatible with base Redis class
     *
     * TODO Redis >= 6.0.0 requires typed properties in methods
     */
    public function testInit(): void
    {
        $redis = new Redis();
    }
}
