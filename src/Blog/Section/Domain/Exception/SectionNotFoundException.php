<?php

namespace App\Blog\Section\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class SectionNotFoundException extends DomainException
{
    public function __construct() {
        parent::__construct('Section not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.section-not-found';
    }
}