<?php

declare(strict_types=1);

namespace App\Image\UseCase;

use App\Image\Entity\Image;
use App\Image\Factory\ImageFactory;
use App\Image\ImageServiceInterface;

class AddImageHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ImageFactory $imageFactory,
        private ImageServiceInterface $imageService,
    ) {
    }

    public function handle(AddImageCommand $addImageCommand): Image
    {
        $image = $this->imageFactory->create($addImageCommand);
        $this->imageService->save($addImageCommand->uploadedFile, $image);

        return $image;
    }
}
