<?php

namespace App\Search\Blog\Application\Service;

use App\Search\Blog\Domain\Entity\Index;
use App\Search\Blog\Domain\Repository\IndexRepositoryInterface;

class IndexService
{
    public function __construct(private IndexRepositoryInterface $indexRepository)
    {
    }

    public function add(Index $index): void
    {
        $this->indexRepository->add($index);
    }
}