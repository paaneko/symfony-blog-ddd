<?php

declare(strict_types=1);

namespace App\Auth\User\Application\EventSubscriber;

use App\Auth\User\Domain\Event\RequestJoinByEmailEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OnRequestJoinByEmailEventSubscriber
{
    public function __invoke(RequestJoinByEmailEvent $event): void
    {
        // TODO implement email send functionality
    }
}
