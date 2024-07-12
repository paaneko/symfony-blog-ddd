<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Repository;

use App\Search\Blog\Domain\Entity\ArticleIndex;

interface IndexRepositoryInterface
{
    public function add(ArticleIndex $index): void;
}
