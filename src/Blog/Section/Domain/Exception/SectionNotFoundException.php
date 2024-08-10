<?php

declare(strict_types=1);

namespace App\Blog\Section\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class SectionNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Section not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.section-not-found';
    }
}
