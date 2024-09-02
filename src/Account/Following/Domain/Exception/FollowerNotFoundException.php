<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class FollowerNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Follower not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.follower-not-found';
    }
}