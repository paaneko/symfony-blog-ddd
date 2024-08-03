<?php

namespace App\Blog\Article\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class ArticleAuthorNotFoundException extends DomainException
{
    public function __construct() {
        parent::__construct('Article author not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.article-author-not-found';
    }
}