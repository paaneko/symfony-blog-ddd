<?php

namespace App\Notification\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class NotificationUserNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Notification user not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.notification.notification-user-not-found';
    }
}