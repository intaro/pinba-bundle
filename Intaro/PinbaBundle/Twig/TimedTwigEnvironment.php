<?php

namespace Intaro\PinbaBundle\Twig;

use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

/**
 * Class TimedTwigEngine
 * @package Intaro\PinbaBundle\Twig
 * Times the time spent to render a template.
 */
class TimedTwigEnvironment extends Environment
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(LoaderInterface $loader, array $options, Stopwatch $stopwatch)
    {
        parent::__construct($loader, $options);
        $this->stopwatch = $stopwatch;
    }

    /**
     * @inheritdoc
     */
    public function render($name, array $context = [])
    {
        $e = $this->stopwatch->start(array(
            'server'        => 'localhost',
            'group'         => 'twig::render',
            'twig_template' => (string)$name,
        ));

        $ret = parent::render($name, $context);

        $e->stop();

        return $ret;
    }
}