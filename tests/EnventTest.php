<?php
use PHPUnit\Framework\TestCase;
use Piko\EventDispatcher;
use Piko\ListenerProvider;
use tests\TestEvent;

class EventTest extends TestCase
{
    public function testEventStopPropagation()
    {
        $provider = new ListenerProvider();

        $provider->addListenerForEvent(TestEvent::class, function(TestEvent $event) {
            $event->value .= 'H';
            $event->stopPropagation();
        });

        $provider->addListenerForEvent(TestEvent::class, function(TestEvent $event) {
            $event->value .= 'i !';
        });

        $dispatcher = new EventDispatcher($provider);
        $event = new TestEvent();
        $dispatcher->dispatch($event);

        $this->assertEquals('H', $event->value);
    }
}
