<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UploadImageCommand
{
    #[Assert\NotBlank]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: 'image/jpeg',
        mimeTypesMessage: 'Please upload a supported JPG image'
    )]
    public UploadedFile $uploadedFile;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }
}
