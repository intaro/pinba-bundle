<?php

namespace Intaro\PinbaBundle;

use DependencyInjection\TwigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class IntaroPinbaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if(Kernel::MAJOR_VERSION >= 3) {
            $container->addCompilerPass(new TwigPass());
        }
    }
}
