<?php

namespace Intaro\PinbaBundle;

use Intaro\PinbaBundle\DependencyInjection\TwigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class IntaroPinbaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TwigPass());
    }
}
