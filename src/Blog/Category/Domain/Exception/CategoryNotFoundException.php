<?php

namespace App\Blog\Category\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class CategoryNotFoundException extends DomainException
{
    public function __construct() {
        parent::__construct('Category not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.category-not-found';
    }
}