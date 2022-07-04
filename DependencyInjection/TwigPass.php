<?php

namespace Intaro\PinbaBundle\DependencyInjection;

use Intaro\PinbaBundle\Twig\TimedTwigEnvironment;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('templating')) {
            return;
        }

        $container->removeDefinition('templating.engine.twig');
        if ($container->hasDefinition('twig')) {
            $definition = $container->getDefinition('twig');
            $definition->setClass(TimedTwigEnvironment::class);
            $definition->addArgument(new Reference('intaro_pinba.stopwatch'));
        }
    }

}
