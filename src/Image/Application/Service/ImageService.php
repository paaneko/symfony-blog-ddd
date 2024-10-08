<?php

declare(strict_types=1);

namespace App\Image\Application\Service;

use App\Image\Domain\Entity\Image;
use App\Image\Domain\Repository\ImageRepositoryInterface;
use App\Image\Domain\ValueObject\ImageId;

final class ImageService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private ImageRepositoryInterface $imageRepository)
    {
    }

    public function find(ImageId $id): ?Image
    {
        return $this->imageRepository->find($id);
    }

    public function add(Image $image): void
    {
        $this->imageRepository->add($image);
    }
}
