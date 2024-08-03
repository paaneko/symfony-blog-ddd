<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\Upload;

use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\Service\UploadServiceInterface;
use App\Image\Domain\ValueObject\ImageId;
use App\Image\Domain\ValueObject\ImageName;
use Doctrine\ORM\EntityManagerInterface;

final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ImageService $imageService,
        private UploadServiceInterface $uploadService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(Command $addImageCommand): ImageId
    {
        $image = new Image(
            $imageId = ImageId::generate(),
            new ImageName($addImageCommand->uploadedFile->getClientOriginalName()),
            false
        );

        $this->uploadService->save($addImageCommand->uploadedFile, $image);

        $this->imageService->add($image);
        $this->entityManager->flush();

        return $imageId;
    }
}
