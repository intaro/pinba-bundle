<?php

namespace Intaro\PinbaBundle\Tests;

use Intaro\PinbaBundle\EventListener\ScriptNameConfigureListener;
use Intaro\PinbaBundle\IntaroPinbaBundle;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Intaro\PinbaBundle\Tests\fixtures\FixtureBundle;
use Intaro\PinbaBundle\Twig\TimedTwigEnvironment;
use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleInitializationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(IntaroPinbaBundle::class);
        $kernel->addTestBundle(FixtureBundle::class);
        $kernel->addTestBundle(TwigBundle::class);
        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testInitBundle(): void
    {
        self::bootKernel();
        $symfony = self::getSymfonyVersion();

        if (version_compare($symfony, '5.0.0') >= 0) {
            $container = self::getContainer();
        } else {
            $container = self::$container;
        }

        $this->assertTrue($container->has(Stopwatch::class));
        $this->assertTrue($container->has('intaro_pinba.stopwatch'));
        $this->assertTrue($container->has(ScriptNameConfigureListener::class));
        $this->assertTrue($container->has('intaro_pinba.script_name_configure.listener'));
    }

    public function testTwigRenderStopwatch(): void
    {
        self::bootKernel();
        $symfony = self::getSymfonyVersion();

        if (version_compare($symfony, '5.0.0') >= 0) {
            $container = self::getContainer();
        } else {
            $container = self::$container;
        }

        $service = $container->get('twig');
        $this->assertInstanceOf(TimedTwigEnvironment::class, $service);
    }

    private static function getSymfonyVersion()
    {
        $kernel = self::bootKernel();

        return $kernel::VERSION;
    }
}
