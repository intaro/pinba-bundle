<?php

namespace Intaro\PinbaBundle\Twig;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Twig\Environment;

class EnvironmentDecorating
{

    private Stopwatch $stopwatch;
    private Environment $twig;

    public function __construct(Environment $twig, Stopwatch $stopwatch)
    {
        $this->twig = $twig;
        $this->stopwatch = $stopwatch;
    }

    public function render($name, array $parameters = []): string
    {
        $e = $this->stopwatch->start([
            'server' => 'localhost',
            'group' => 'twig::render',
            'twig_template' => (string) $name,
        ]);

        $result = $this->twig->render($name, $parameters);

        $e->stop();

        return $result;
    }
}
