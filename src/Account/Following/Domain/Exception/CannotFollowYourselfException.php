<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class CannotFollowYourselfException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot follow yourself');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.cannot-follow-yourself';
    }
}