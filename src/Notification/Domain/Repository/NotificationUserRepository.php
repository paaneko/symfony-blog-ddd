<?php

namespace App\Notification\Domain\Repository;

use App\Notification\Application\Dto\NotificationUserDto;

interface NotificationUserRepository
{
    public function getById(string $id): NotificationUserDto;
}