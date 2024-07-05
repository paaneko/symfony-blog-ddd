<?php

declare(strict_types=1);

namespace App\Http\Image;

use App\Image\UseCase\AddImageCommand;
use App\Image\UseCase\AddImageHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class UploadImageController extends AbstractController
{
    #[Route('/image', methods: ['POST'])]
    public function __invoke(Request $request, AddImageHandler $addImageHandler, ValidatorInterface $validator): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');

        $addImageCommand = new AddImageCommand($uploadedFile);
        $validator->validate($addImageCommand);

        $image = $addImageHandler->handle($addImageCommand);

        return $this->json($image, Response::HTTP_OK);
    }
}
