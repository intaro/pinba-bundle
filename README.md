# PinbaBundle #
![CI](https://github.com/intaro/pinba-bundle/workflows/CI/badge.svg?branch=master)

## About ##

PinbaBundle is Symfony bundle for [pinba](http://pinba.org). 

    Important! Include this bundle only in `prod` environment.

It collects and sends times of execution for Doctrine queries, Twig renders and Redis requests as pinba timers to pinba server. You can watch collected realtime metrics in [Intaro Pinboard](http://intaro.github.io/pinboard/). Example of output:

![Pinba timers in Intaro Pinboard](http://intaro.github.io/pinboard/img/timers.png)

Also PinbaBundle changes pinba `script_name` variable to `request_uri` value otherwise pinba sends `app.php` in `script_name` for the requests.

## Installation ##

PinbaBundle requires Symfony 4.4 or higher.

Require the bundle in your `composer.json` file:

```json
{
    "require": {
        "intaro/pinba-bundle": "^2.0",
    }
}
```

**Important!** Register the bundle in `prod` environment:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        //...
    ];

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

## Development ##

### Run tests ###

Install vendors:
```shell
make vendor
```

Run php-cs-fixer, phpstan and phpunit:
```shell
make check
```
