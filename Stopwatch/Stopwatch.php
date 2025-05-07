<?php

namespace Intaro\PinbaBundle\Stopwatch;

class Stopwatch
{
    private const FLUSH_TIMERS_LIMIT = 1000;

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
            && function_exists('pinba_timers_get')
            && function_exists('pinba_flush')
        ;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function start(array $tags): StopwatchEvent
    {
        if (!$this->enabled) {
            return new StopwatchEvent();
        }

        $this->flushIfTimersLimitReached();

        $tags = array_merge($this->initTags, $tags);
        if (isset($tags['group']) && !isset($tags['category']) && false !== strpos($tags['group'], '::')) {
            $v = explode('::', $tags['group']);
            $tags['category'] = $v[0];
        }

        return new StopwatchEvent(pinba_timer_start($tags));
    }

    public function add(array $tags, $time): void
    {
        if (!$this->enabled) {
            return;
        }

        $this->flushIfTimersLimitReached();

        $tags = array_merge($this->initTags, $tags);
        pinba_timer_add($tags, $time);
    }

    private function flushIfTimersLimitReached(): void
    {
        $timersCount = count(pinba_timers_get(PINBA_ONLY_STOPPED_TIMERS));
        if ($timersCount >= self::FLUSH_TIMERS_LIMIT) {
            pinba_flush(null, PINBA_FLUSH_ONLY_STOPPED_TIMERS);
        }
    }
}
