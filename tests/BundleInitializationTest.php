<?php

namespace Intaro\PinbaBundle\Tests;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
        $kernel->addTestBundle(IntaroTwigSandboxBundle::class);
        $kernel->addTestBundle(FixtureBundle::class);
        $kernel->handleOptions($options);

        return $kernel;
    }

    public function testInitBundle(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->assertTrue($container->has(EnvironmentBuilder::class));
        $this->assertTrue($container->has(\Intaro\TwigSandboxBundle\Builder\EnvironmentBuilder::class));
        $service = $container->get(EnvironmentBuilder::class);
        $this->assertInstanceOf(EnvironmentBuilder::class, $service);
    }

    public function testRender(): void
    {
        self::bootKernel();
        $container = self::$container;

        $twig = $container->get(EnvironmentBuilder::class)->getSandboxEnvironment();
        $tpl = $twig->createTemplate('Product {{ product.name }}');

        $html = $tpl->render([
            'product' => $this->getObject(),
        ]);

        $this->assertEquals('Product Product 1', $html);
    }

    public function testRenderWithFilter(): void
    {
        self::bootKernel();
        $container = self::$container;

        $twig = $container->get(EnvironmentBuilder::class)->getSandboxEnvironment();
        $tpl = $twig->createTemplate('Product {{ product.name|lower }}');

        $html = $tpl->render([
            'product' => $this->getObject(),
        ]);

        $this->assertEquals('Product product 1', $html);
    }

    public function testRenderError(): void
    {
        $this->expectException(SecurityNotAllowedMethodError::class);
        $this->expectExceptionMessageMatches('/Calling "getquantity" method on a ".*Product" object is not allowed in/');

        self::bootKernel();
        $container = self::$container;

        $twig = $container->get(EnvironmentBuilder::class)->getSandboxEnvironment();
        $tpl = $twig->createTemplate('Product {{ product.quantity }}');

        $tpl->render([
            'product' => $this->getObject(),
        ]);
    }

    public function testRenderWithEmptyConfig(): void
    {
        $this->expectException(SecurityNotAllowedFilterError::class);
        $this->expectExceptionMessageMatches('/Filter "lower" is not allowed in/');

        $kernel = self::bootKernel(['config' => static function (TestKernel $kernel): void {
            $kernel->addTestBundle(IntaroTwigSandboxBundle::class);
            $kernel->addTestBundle(FixtureBundle::class);

            $kernel->addTestConfig(__DIR__ . '/fixtures/empty-config.yml');
        }]);

        $container = self::$container;
        $twig = $container->get(EnvironmentBuilder::class)->getSandboxEnvironment();
        $tpl = $twig->createTemplate('Product {{ product.name|lower }}');

        $html = $tpl->render([
            'product' => $this->getObject(),
        ]);

        $this->assertEquals('Product product 1', $html);
    }

    private function getObject(): Product
    {
        $product = new Product();
        $product->setName('Product 1');
        $product->setQuantity(5);

        return $product;
    }
}
