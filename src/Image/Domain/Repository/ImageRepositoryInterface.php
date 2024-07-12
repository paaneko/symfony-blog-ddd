<?php

declare(strict_types=1);

namespace App\Image\Domain\Repository;

use App\Image\Domain\Entity\Image;
use App\Image\Domain\ValueObject\ImageId;

interface ImageRepositoryInterface
{
    public function find(ImageId $id): ?Image;

    public function add(Image $image): void;
}
