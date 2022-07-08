<?php

namespace Intaro\PinbaBundle\EventListener;

use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ScriptNameConfigureListener
{
    public function onRequest(KernelEvent $event): void
    {
        if (self::bcEvent($event) || !$event instanceof RequestEvent) {
            throw new \InvalidArgumentException('Event must be GetResponseEvent or RequestEvent');
        }

        if (HttpKernelInterface::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (!function_exists('pinba_script_name_set') || \PHP_SAPI === 'cli') {
            return;
        }

        pinba_script_name_set($event->getRequest()->getRequestUri());
    }

    private static function bcEvent($event)
    {
        $eventClass = \Symfony\Component\HttpKernel\Event\GetResponseEvent::class;

        return class_exists($eventClass) && !$event instanceof $eventClass;
    }
}
