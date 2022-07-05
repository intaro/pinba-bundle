<?php

namespace Intaro\PinbaBundle\Stopwatch;

class Stopwatch
{
    protected $enabled = false;
    protected $initTags = [];

    public function __construct()
    {
        $this->enable();
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

    public function enable(): void
    {
        $this->enabled =
            function_exists('pinba_timer_start')
            && function_exists('pinba_timer_stop')
            && function_exists('pinba_timer_add')
            && function_exists('pinba_get_info')
            ;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function start(array $tags)
    {
        if ($this->enabled) {
            $tags = array_merge($this->initTags, $tags);
            if (isset($tags['group']) && !isset($tags['category']) && false !== strpos($tags['group'], '::')) {
                $v = explode('::', $tags['group']);
                if (count($v) > 0) {
                    $tags['category'] = $v[0];
                }
            }
        }

        return new StopwatchEvent($this->enabled ? pinba_timer_start($tags) : null);
    }

    public function add(array $tags, $time): void
    {
        if (!$this->enabled) {
            return;
        }

        $tags = array_merge($this->initTags, $tags);

        pinba_timer_add($tags, $time);
    }
}
