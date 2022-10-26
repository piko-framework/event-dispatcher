<?php

/**
 * @copyright 2022 Sylvain Philip
 * @license MIT; see LICENSE.md
 * @link https://github.com/piko-framework/event-dispatcher
 */

declare(strict_types=1);

namespace Piko;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Event dispatcher implementatiion of the PSR-14 EventDispatcherInterface
 *
 * @link https://www.php-fig.org/psr/psr-14/
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    private $listenerProvider;

    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * {@inheritDoc}
     * @see \Psr\EventDispatcher\EventDispatcherInterface::dispatch()
     */
    public function dispatch(object $event): object
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event);
        }

        return $event;
    }
}
