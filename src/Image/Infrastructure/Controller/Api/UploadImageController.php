<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Controller\Api;

use App\Image\Application\UseCase\Upload\Command;
use App\Image\Application\UseCase\Upload\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class UploadImageController extends AbstractController
{
    #[Route('/image', methods: ['POST'])]
    public function __invoke(Request $request, Handler $addImageHandler, ValidatorInterface $validator): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');

        $addImageCommand = new Command($uploadedFile);
        $validator->validate($addImageCommand);

        $responseData = $addImageHandler->handle($addImageCommand);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
