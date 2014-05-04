# PinbaBundle #

## About ##

PinbaBundle is Symfony bundle for [pinba](http://pinba.org). 

    Important! Include this bundle only in `prod` environment.

It aggregate and send times of execution for Doctrine queries, Twig renders and Memcache requests as pinba timers in production environment. You can watch this realtime metrics in [Intaro Pinboard](http://intaro.github.io/pinboard/). Example of output:

![Pinba timers in Intaro Pinboard](http://intaro.github.io/pinboard/img/timers.png)

Also PinbaBundle changes pinba `script_name` variable to `request_uri` value otherwise pinba sends `app.php` in `script_name` for the requests.

## Installation ##

PinbaBundle requires Symfony 2.1 or higher.

Require the bundle in your `composer.json` file:

````json
{
    "require": {
        "intaro/pinba-bundle": "~0.1",
    }
}
```

**Important!** Register the bundle in `prod` environment:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        //...
    );

    if ('prod' === $this->environment) {
        $bundles[] = new Intaro\PinbaBundle\IntaroPinbaBundle();
    }

    //...
}
```

Install the bundle:

```
$ composer update intaro/pinba-bundle
```
