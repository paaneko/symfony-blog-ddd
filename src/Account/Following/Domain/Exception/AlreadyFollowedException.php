<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class AlreadyFollowedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Already followed');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.already-followed';
    }
}