<?php

namespace App\Notification\Application\EventSubscriber;

use App\Notification\Domain\Event\SendArticleCreatedEmailNotificationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OnSendArticleCreatedEmailNotificationEventSubscriber
{
    public function __invoke(SendArticleCreatedEmailNotificationEvent $event): void
    {
        // TODO implement email send functionality
    }
}