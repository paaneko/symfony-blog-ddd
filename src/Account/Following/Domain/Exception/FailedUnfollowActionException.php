<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class FailedUnfollowActionException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Failed unfollow action');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.failed-unfollow-action';
    }
}