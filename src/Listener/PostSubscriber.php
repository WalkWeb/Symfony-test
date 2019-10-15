<?php

namespace App\Listener;

use App\Event\PostCreateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostSubscriber implements EventSubscriberInterface
{
    public function onPostCreate(): void
    {
        var_dump('onPostCreate');
    }

    public function onPostUpdate(): void
    {
        var_dump('onPostUpdate');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostCreateEvent::class => [
                ['onPostCreate'],
                ['onPostUpdate'],
            ],
        ];
    }
}
