<?php

namespace App\Search\Blog\Infrastructure\Repository;

use App\Search\Blog\Domain\Entity\Index;
use App\Search\Blog\Domain\Repository\IndexRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostgresIndexRepository extends ServiceEntityRepository implements IndexRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Index::class);
    }

    public function add(Index $index): void
    {
        $this->getEntityManager()->persist($index);
    }
}