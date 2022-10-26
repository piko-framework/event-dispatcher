<?php

/**
 * @copyright 2022 Sylvain Philip
 * @license MIT; see LICENSE.md
 * @link https://github.com/piko-framework/event-dispatcher
 */

declare(strict_types=1);

namespace Piko;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Event implementation of the PSR-14 StoppableEventInterface
 *
 * @link https://www.php-fig.org/psr/psr-14/
 */
class Event implements StoppableEventInterface
{
    /**
     * @var boolean
     */
    private $stopPropagation = false;

    /**
     * {@inheritDoc}
     * @see \Psr\EventDispatcher\StoppableEventInterface::isPropagationStopped()
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }

    /**
     * Stop the propagation of the event in the event dispatcher
     */
    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }
}
