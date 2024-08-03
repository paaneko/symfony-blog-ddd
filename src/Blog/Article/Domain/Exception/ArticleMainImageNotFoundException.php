<?php

namespace App\Blog\Article\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class ArticleMainImageNotFoundException extends DomainException
{
    public function __construct() {
        parent::__construct('Article main image not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.article-main-image-not-found';
    }
}