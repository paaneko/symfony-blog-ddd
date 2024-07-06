<?php

declare(strict_types=1);

namespace App\Image;

use App\Image\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageServiceInterface
{
    public function save(UploadedFile $uploadedFile, Image $image): void;
}
