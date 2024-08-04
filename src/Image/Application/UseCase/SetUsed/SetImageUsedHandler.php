<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\SetUsed;

use App\Image\Application\Service\ImageService;
use App\Image\Domain\ValueObject\ImageId;
use App\SharedKernel\Domain\Bus\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SetImageUsedHandler
{
    public function __construct(
        private ImageService $imageService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(SetImageUsedCommand $setUsedCommand): void
    {
        $image = $this->imageService->find(new ImageId($setUsedCommand->imageId));

        if (is_null($image)) {
            throw new \DomainException('Image not found');
            // Add logger, and alarm because this critical error, that does not suppose to happen never
            // Otherwise we can dispatch event that removes article
        }

        $image->setUsed();

        $this->entityManager->flush();
    }
}
