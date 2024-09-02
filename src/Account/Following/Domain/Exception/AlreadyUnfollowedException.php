<?php

namespace App\Account\Following\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class AlreadyUnfollowedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Already unfollowed');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.account.following.already-unfollowed';
    }
}