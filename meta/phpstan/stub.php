<?php

if (!function_exists('pinba_get_info')) {
    function pinba_get_info(): array
    {
        return [];
    }
}

if (!function_exists('pinba_timer_start')) {
    function pinba_timer_start(array $tags)
    {
    }
}

if (!function_exists('pinba_timer_stop')) {
    function pinba_timer_stop($timer)
    {
    }
}

if (!function_exists('pinba_timer_add')) {
    function pinba_timer_add(array $tags, int $value)
    {
    }
}

if (!function_exists('pinba_timers_get')) {
    function pinba_timers_get(int $flag = 0): array
    {
        return [];
    }
}

if (!function_exists('pinba_flush')) {
    function pinba_flush(?string $scriptName = null, int $flags = 0): void
    {
    }
}

if (!defined('PINBA_ONLY_STOPPED_TIMERS')) {
    define('PINBA_ONLY_STOPPED_TIMERS', 1);
}

if (!defined('PINBA_FLUSH_ONLY_STOPPED_TIMERS')) {
    define('PINBA_FLUSH_ONLY_STOPPED_TIMERS', 1);
}
