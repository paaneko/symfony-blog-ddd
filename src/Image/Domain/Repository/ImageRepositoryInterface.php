<?php

declare(strict_types=1);

namespace App\Image\Domain\Repository;

use App\Image\Domain\Entity\Id;
use App\Image\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    public function find(Id $id): ?Image;

    public function add(Image $image): void;
}
