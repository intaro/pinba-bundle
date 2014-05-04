<?php

namespace Intaro\PinbaBundle\Stopwatch;

class Stopwatch
{
    protected $enabled = false;

    public function __construct()
    {
        $this->enabled =
            function_exists('pinba_timer_start') &&
            function_exists('pinba_timer_stop') &&
            function_exists('pinba_timer_add')
            ;
    }

    public function start($tags)
    {
        return new StopwatchEvent($this->enabled ? pinba_timer_start($tags) : null);
    }

    public function add($tags, $time)
    {
        if (!$this->enabled)
            return;

        pinba_timer_add($tags, $time);
    }
}