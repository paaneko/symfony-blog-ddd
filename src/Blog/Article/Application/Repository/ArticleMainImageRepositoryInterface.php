<?php

namespace App\Blog\Article\Application\Repository;

use App\Blog\Article\Application\Dto\ArticleMainImageDto;

interface ArticleMainImageRepositoryInterface
{
    public function getById(string $id): ArticleMainImageDto;
}