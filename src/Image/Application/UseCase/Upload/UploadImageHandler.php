<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\Upload;

use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\Service\UploadServiceInterface;
use App\Image\Domain\ValueObject\ImageId;
use App\Image\Domain\ValueObject\ImageName;
use App\SharedKernel\Domain\Bus\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UploadImageHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ImageService $imageService,
        private UploadServiceInterface $uploadService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UploadImageCommand $addImageCommand): ImageId
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
