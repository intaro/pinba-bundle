<?php

namespace Intaro\PinbaBundle;

use Intaro\PinbaBundle\DependencyInjection\TwigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IntaroPinbaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigPass());
    }
}
