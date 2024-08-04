<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Controller\Api;

use App\Image\Application\UseCase\Upload\UploadImageCommand;
use App\Image\Application\UseCase\Upload\UploadImageHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class UploadImageController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/image', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');

        $addImageCommand = new UploadImageCommand($uploadedFile);

        $this->commandBus->dispatch($addImageCommand);

        return $this->json('', Response::HTTP_CREATED);
    }
}
