<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Intaro\PinbaBundle\Twig;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Times the time spent to render a template.
 *
 */
class TimedTwigEngine extends TwigEngine
{
    protected $stopwatch;

    /**
     * Constructor.
     *
     * @param \Twig\Environment           $environment A \Twig\Environment instance
     * @param TemplateNameParserInterface $parser      A TemplateNameParserInterface instance
     * @param FileLocatorInterface        $locator     A FileLocatorInterface instance
     * @param Stopwatch                   $stopwatch   A Stopwatch instance
     */
    public function __construct(\Twig\Environment $environment, TemplateNameParserInterface $parser, FileLocatorInterface $locator, Stopwatch $stopwatch)
    {
        parent::__construct($environment, $parser, $locator);

        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = array())
    {
        $e = $this->stopwatch->start(array(
            'server' => 'localhost',
            'group' => 'twig::render',
            'twig_template' => (string) $name,
        ));

        $ret = parent::render($name, $parameters);

        $e->stop();

        return $ret;
    }
}
