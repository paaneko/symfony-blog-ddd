<?php

declare(strict_types=1);

namespace App\Blog\Section\Infrastructure\Repository;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\Repository\SectionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress UnusedClass
 *
 * @phpstan-ignore-next-line
 */
final class PostgresSectionRepository extends ServiceEntityRepository implements SectionRepositoryInterface
{
    /** @psalm-suppress PossiblyUnusedParam */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function add(Section $section): void
    {
        $this->getEntityManager()->persist($section);
    }
}
