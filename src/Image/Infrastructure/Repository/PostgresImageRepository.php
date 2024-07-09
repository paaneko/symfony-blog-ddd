<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Repository;

use App\Image\Domain\Entity\Id;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\Repository\ImageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/** @psalm-suppress UnusedClass */
class PostgresImageRepository implements ImageRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function find(Id $id): ?Image
    {
        return $this->entityManager->find(Image::class, $id);
    }

    public function add(Image $image): void
    {
        $this->entityManager->persist($image);
    }
}
