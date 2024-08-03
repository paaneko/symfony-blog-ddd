<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Controller\Api;

use App\Image\Application\UseCase\Upload\UploadImageCommand;
use App\Image\Application\UseCase\Upload\UploadImageHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class UploadImageController extends AbstractController
{
    #[Route('/image', methods: ['POST'])]
    public function __invoke(Request $request, UploadImageHandler $addImageHandler, ValidatorInterface $validator): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');

        $addImageCommand = new UploadImageCommand($uploadedFile);
        $validator->validate($addImageCommand);

        $responseData = $addImageHandler->handle($addImageCommand);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
