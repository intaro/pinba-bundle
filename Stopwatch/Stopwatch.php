<?php

namespace Intaro\PinbaBundle\Stopwatch;

class Stopwatch
{
    protected $enabled = false;
    protected $initTags = array();

    public function __construct()
    {
        $this->enabled =
            function_exists('pinba_timer_start') &&
            function_exists('pinba_timer_stop') &&
            function_exists('pinba_timer_add') &&
            function_exists('pinba_get_info')
            ;

        if ($this->enabled) {
            $pinbaData = pinba_get_info();

            if (isset($pinbaData['hostname'])) {
                $this->initTags['__hostname'] = $pinbaData['hostname'];
            }
            if (isset($pinbaData['server_name'])) {
                $this->initTags['__server_name'] = $pinbaData['server_name'];
            }
        }
    }

    public function start(array $tags)
    {
        $tags = array_merge($this->initTags, $tags);

        return new StopwatchEvent($this->enabled ? pinba_timer_start($tags) : null);
    }

    public function add(array $tags, $time)
    {
        if (!$this->enabled)
            return;

        $tags = array_merge($this->initTags, $tags);

        pinba_timer_add($tags, $time);
    }
}