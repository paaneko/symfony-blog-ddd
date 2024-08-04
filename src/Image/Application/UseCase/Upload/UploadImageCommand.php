<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\Upload;

use App\SharedKernel\Domain\Bus\CommandInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UploadImageCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $preparedId;

    #[Assert\NotBlank]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: 'image/jpeg',
        mimeTypesMessage: 'Please upload a supported JPG image'
    )]
    public UploadedFile $uploadedFile;

    public function __construct(
        string $preparedId,
        UploadedFile $uploadedFile
    )
    {
        $this->preparedId = $preparedId;
        $this->uploadedFile = $uploadedFile;
    }
}
