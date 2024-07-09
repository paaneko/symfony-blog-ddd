<?php

declare(strict_types=1);

namespace App\Image\Domain\Service;

use App\Image\Domain\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadServiceInterface
{
    public function save(UploadedFile $uploadedFile, Image $image): void;
}
