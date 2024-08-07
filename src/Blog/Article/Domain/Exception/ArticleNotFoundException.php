<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class ArticleNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Article not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.article-not-found';
    }
}
