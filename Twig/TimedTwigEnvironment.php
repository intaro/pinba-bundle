<?php

namespace Intaro\PinbaBundle\Twig;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

class TimedTwigEnvironment extends Environment
{
    private Stopwatch $stopwatch;

    public function __construct(LoaderInterface $loader, $options = [], Stopwatch $stopwatch)
    {
        parent::__construct($loader, $options);

        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = [])
    {
        $e = $this->stopwatch->start([
            'server' => 'localhost',
            'group' => 'twig::render',
            'twig_template' => (string) $name,
        ]);

        $ret = parent::render($name, $parameters);

        $e->stop();

        return $ret;
    }
}
