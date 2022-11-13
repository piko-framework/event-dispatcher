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

    public function testMultipleDispatchForOneListener()
    {
        $provider = new ListenerProvider();

        $provider->addListenerForEvent(TestEvent::class, function(TestEvent $event) {
            $event->value++;
        });

        $dispatcher = new EventDispatcher($provider);
        $event = new TestEvent();
        $event->value = 0;

        for ($i = 0; $i < 5; $i++) {
            $dispatcher->dispatch($event);
        }

        $this->assertEquals(5, $event->value);
    }
}
