<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\Service;

use App\Search\Blog\Domain\Entity\ArticleIndex;
use App\Search\Blog\Domain\Repository\IndexRepositoryInterface;

class IndexService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private IndexRepositoryInterface $indexRepository)
    {
    }

    public function add(ArticleIndex $index): void
    {
        $this->indexRepository->add($index);
    }
}
