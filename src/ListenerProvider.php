<?php

/**
 * @copyright 2022 Sylvain Philip
 * @license MIT; see LICENSE.md
 * @link https://github.com/piko-framework/event-dispatcher
 */

declare(strict_types=1);

namespace Piko;

use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

/**
 * Implementation of ListernerProviderInterface using a priority queue.
 *
 * @link https://www.php-fig.org/psr/psr-14/
 */
class ListenerProvider implements ListenerProviderInterface
{
    const INT_PRIORITY_DEFAULT = 20000000;

    /**
     * @var array<string, SplPriorityQueue<int, callable>>
     */
    private $listeners = [];

    /**
     * @var array<int>
     */
    private $priorities = [];

    /**
     * Event listener registration.
     *
     * @param string $eventClassName The name of the event class to listen.
     * @param callable $callback The callback that will receives the event as unique argument.
     * Must be  one of the following:
     *                    - A Closure (function(){ ... })
     *                    - An object method ([$object, 'methodName'])
     *                    - A static class method ('MyClass::myMethod')
     *                    - A global function ('myFunction')
     *                    - An object implementing __invoke()
     * @param int|null $priority The order priority in the event queue. Default to null.
     *
     * @return void
     */
    public function addListenerForEvent(string $eventClassName, callable $callback, ?int $priority = null): void
    {
        if (!isset($this->listeners[$eventClassName])) {
            $this->listeners[$eventClassName] = new SplPriorityQueue();
            $this->priorities[$eventClassName] = static::INT_PRIORITY_DEFAULT;
        }

        $priority = $priority === null ? $this->priorities[$eventClassName]-- : static::INT_PRIORITY_DEFAULT + $priority;

        $this->listeners[$eventClassName]->insert($callback, $priority);
    }

    /**
     * {@inheritDoc}
     * @see \Psr\EventDispatcher\ListenerProviderInterface::getListenersForEvent()
     *
     * @return \Generator<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners[get_class($event)] ?? [] as $listener) {
            yield $listener;
        }
    }
}
