<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/** @psalm-suppress UnusedClass */
final class CreateArticleController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    #[Route('/article', name: "create_article", methods: ['POST'])]
    #[OA\Post(
        path: '/article',
        operationId: "createArticle",
        summary: 'Create a new article',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'content', 'categoryId', 'authorId', 'imageId'],
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'Lorem ipsum dolor sit amet'
                    ),
                    new OA\Property(property: 'content', type: 'string'),
                    new OA\Property(property: 'categoryId', type: 'string'),
                    new OA\Property(property: 'sectionId', type: 'string'),
                    new OA\Property(property: 'authorId', type: 'string'),
                    new OA\Property(property: 'imageId', type: 'string'),
                ]
            )
        ),
        tags: ["Article"],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response'
            )
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

        $command = new CreateArticleCommand(
            $parameters['title'],
            $parameters['content'],
            $parameters['categoryId'],
            $parameters['sectionId'] ?? null,
            $parameters['authorId'],
            $parameters['imageId'],
        );

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
