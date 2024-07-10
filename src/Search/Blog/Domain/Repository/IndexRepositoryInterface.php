<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Repository;

use App\Search\Blog\Domain\Entity\Index;

interface IndexRepositoryInterface
{
    public function add(Index $index): void;
}
