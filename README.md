# Piko event-dispatcher

[![Tests](https://github.com/piko-framework/event-dispatcher/actions/workflows/php.yml/badge.svg)](https://github.com/piko-framework/event-dispatcher/actions/workflows/php.yml)
[![Coverage Status](https://coveralls.io/repos/github/piko-framework/event-dispatcher/badge.svg?branch=main)](https://coveralls.io/github/piko-framework/event-dispatcher?branch=main)

This package offers a simple event dispatcher using an event priority queue, following the [PSR-14](https://www.php-fig.org/psr/psr-14/) Event Dispatcher recommendation.

## Installation

Via Composer

```bash
composer require piko/event-dispatcher
```

Then ensure that the following file is included in your PHP project:

```php
require 'vendor/autoload.php'; // The Composer autoloader
```

## usage

```php
use Piko\Event;
use Piko\ListenerProvider;
use Piko\EventDispatcher;

class MyEvent extends \Piko\Event
{
    public $value;
}

$provider = new ListenerProvider();
$dispatcher = new EventDispatcher($provider);
$event = new MyEvent();

$provider->addListenerForEvent(MyEvent::class, function(MyEvent $event) {
    $event->value .= 'World !';
});

$provider->addListenerForEvent(MyEvent::class, function(MyEvent $event) {
    $event->value .= 'Hello ';
}, 10); // Set the priority to 10

$dispatcher->dispatch($event);

echo $event->value; // Hello World!

```

