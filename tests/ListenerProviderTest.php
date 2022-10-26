<?php
use PHPUnit\Framework\TestCase;
use Piko\EventDispatcher;
use Piko\ListenerProvider;
use tests\TestEvent;

class ListenerProviderTest extends TestCase
{
    public static function ontestEvent(TestEvent $event)
    {
        $event->value .= 'l';
    }

    public function testAddingListenersWithPriority()
    {
        $provider = new ListenerProvider();

        $provider->addListenerForEvent(TestEvent::class, function(TestEvent $event) {
            $event->value .= 'o';
        });

        $provider->addListenerForEvent(TestEvent::class, new class {
            public function __invoke(TestEvent $event)
            {
                $event->value .= 'c';
            }
        }, 200);

        $provider->addListenerForEvent(TestEvent::class, [
            new class {
                public function onTestEvent(TestEvent $event)
                {
                    $event->value .= 'o';
                }
            },
            'onTestEvent'
         ]);

        $provider->addListenerForEvent(TestEvent::class, 'ListenerProviderTest::ontestEvent');

        $provider->addListenerForEvent(TestEvent::class, function(TestEvent $event) {
            $event->value .= 'Keep ';
        }, 201);

        $dispatcher = new EventDispatcher($provider);

        $event = new TestEvent();

        $dispatcher->dispatch($event);

        $this->assertEquals('Keep cool', $event->value);
    }
}
