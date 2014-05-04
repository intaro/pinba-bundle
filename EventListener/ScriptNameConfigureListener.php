<?php

namespace Intaro\PinbaBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ScriptNameConfigureListener
{
    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (!function_exists('pinba_script_name_set') || PHP_SAPI === 'cli') {
            return;
        }

        pinba_script_name_set($event->getRequest()->getRequestUri());
    }
}