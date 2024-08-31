<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class FailedFollowActionException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Failed follow action');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.failed-follow-action';
    }
}