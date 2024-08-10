<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Controller\Api;

use App\Image\Application\UseCase\Upload\UploadImageCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Factory\UuidFactory;

/** @psalm-suppress UnusedClass */
final class UploadImageController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private UuidFactory $uuidFactory
    ) {
    }

    #[Route('/uploadImage', name: 'upload_image', methods: ['POST'])]
    #[OA\Post(
        path: '/uploadImage',
        operationId: 'uploadImage',
        summary: 'Uploads an image and returns the generated image UUID.',
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['image'],
                    properties: [
                        new OA\Property(
                            property: 'image',
                            description: 'The image file to upload.',
                            type: 'string',
                            format: 'binary'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Media - Image'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'Image successfully uploaded.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'imageId',
                            description: 'The UUID of the uploaded image.',
                            type: 'string',
                            example: '123e4567-e89b-12d3-a456-426614174000'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(Request $request): Response
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $preparedId = (string) $this->uuidFactory->create();

        $addImageCommand = new UploadImageCommand(
            $preparedId,
            $uploadedFile
        );

        $this->commandBus->dispatch($addImageCommand);

        return $this->json(['imageId' => $preparedId], Response::HTTP_CREATED);
    }
}
