<?php

declare(strict_types=1);

namespace App\Image\Factory;

use App\Image\Entity\Id;
use App\Image\Entity\Image;
use App\Image\UseCase\AddImageCommand;

class ImageFactory
{
    public function create(AddImageCommand $addImageCommand): Image
    {
        return new Image(
            Id::generate(),
            $addImageCommand->uploadedFile->getClientOriginalName()
        );
    }
}
