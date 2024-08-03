<?php

declare(strict_types=1);

namespace App\Image\Application\Service;

use App\Image\Domain\Entity\Image;
use App\Image\Domain\Service\UploadServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/** @psalm-suppress UnusedClass */
final class LocalUploadService implements UploadServiceInterface
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    public function save(UploadedFile $uploadedFile, Image $image): void
    {
        /** @phpstan-ignore-next-line */
        $imageDir = $this->parameterBag->get('images_dir') . '/' . $image->getId()->getValue();
        $uploadedFile->move($imageDir, $image->getName()->getValue());
    }
}
