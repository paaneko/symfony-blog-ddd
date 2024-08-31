<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class FolloweeNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Followee not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.followee-not-found';
    }
}