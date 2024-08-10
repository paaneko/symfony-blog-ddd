<?php

declare(strict_types=1);

namespace App\Blog\Section\Infrastructure\Controller\Api;

use App\Blog\Section\Application\UseCase\Create\CreateSectionCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/** @psalm-suppress UnusedClass */
final class CreateSectionController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/section', name: 'create_section', methods: ['POST'])]
    #[OA\Post(
        path: '/section',
        operationId: 'createSection',
        summary: 'Create section',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                ],
                type: 'object'
            )
        ),
        tags: ['Article - Section'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response',
            ),
        ]
    )]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $command = new CreateSectionCommand($parameters['name']);

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
