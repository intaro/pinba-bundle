# PinbaBundle #

## About ##

PinbaBundle is Symfony bundle for [pinba](http://pinba.org). 

    Important! Include this bundle only in `prod` environment.

It collects and sends times of execution for Doctrine queries, Twig renders and Memcache requests as pinba timers to pinba server. You can watch collected realtime metrics in [Intaro Pinboard](http://intaro.github.io/pinboard/). Example of output:

![Pinba timers in Intaro Pinboard](http://intaro.github.io/pinboard/img/timers.png)

Also PinbaBundle changes pinba `script_name` variable to `request_uri` value otherwise pinba sends `app.php` in `script_name` for the requests.

## Installation ##

PinbaBundle requires Symfony 2.1 or higher.

Require the bundle in your `composer.json` file:

````json
{
    "require": {
        "intaro/pinba-bundle": "0.1",
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

## Usage ##

### Configure script_name ###

PinbaBundle automatically configures `script_name` variable of pinba.

### Collecting Twig metrics ###

PinbaBundle automatically collects metrics for Twig renders.

### Collecting Doctrine metrics ###

Edit `app/config/config_prod.yml` and add this lines:
```yml
doctrine:
    dbal:
        logging: true
```

Don't worry. This config enables pinba logger which collects only queries execution time but not logs them.

### Collecting Memcache metrics ###

PinbaBundle supplies Memcache wrapped class `Intaro\PinbaBundle\Cache\Memcache` which collects execution times of all memcache quiries.

Example of `app/config/config_prod.yml`:
```yml
services:
    memcache.db:
        class: Intaro\PinbaBundle\Cache\Memcache
        calls:
            - [ addServer, [ %memcache.host%, %memcache.port% ]]
            - [ setStopwatch, [ @intaro_pinba.stopwatch ]]
    doctrine.metadata.memcache:
        class: Doctrine\Common\Cache\MemcacheCache
        calls:
            - [ setMemcache, [ @memcache.db ]]
    doctrine.query.memcache:
        class: Doctrine\Common\Cache\MemcacheCache
        calls:
            - [ setMemcache, [ @memcache.db ]]
    doctrine.result.memcache:
        class: Doctrine\Common\Cache\MemcacheCache
        calls:
            - [ setMemcache, [ @memcache.db ]]

doctrine:
   orm:
       entity_managers:
           default:
               metadata_cache_driver:
                   type: service
                   id:   doctrine.metadata.memcache
               query_cache_driver:
                   type: service
                   id:   doctrine.query.memcache
               result_cache_driver:
                   type: service
                   id:   doctrine.result.memcache
```
