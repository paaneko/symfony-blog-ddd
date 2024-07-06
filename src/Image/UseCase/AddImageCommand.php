<?php

declare(strict_types=1);

namespace App\Image\UseCase;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Rule;

class AddImageCommand
{
    #[Rule\NotBlank(message: 'Image is required.')]
    #[Rule\File(
        maxSize: '5M',
        mimeTypesMessage: 'Please upload a supported JPG image'
    )]
    public UploadedFile $uploadedFile;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }
}
