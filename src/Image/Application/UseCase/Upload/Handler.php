<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\Upload;

use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Id;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\Service\UploadServiceInterface;
use App\Image\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ImageService $imageService,
        private UploadServiceInterface $uploadService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(Command $addImageCommand): Id
    {
        $image = new Image(
            $imageId = Id::generate(),
            new Name($addImageCommand->uploadedFile->getClientOriginalName()),
            false
        );

        $this->uploadService->save($addImageCommand->uploadedFile, $image);

        $this->imageService->add($image);
        $this->entityManager->flush();

        return $imageId;
    }
}
