<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Get\GetArticleFetcher;
use App\Blog\Article\Application\UseCase\Get\GetArticleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

/** @psalm-suppress UnusedClass */
final class GetArticleController extends AbstractController
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    #[Route('/article/{uuid}', name: "get_article", methods: ['GET'])]
    #[OA\Get(
        path: '/article/{uuid}',
        operationId: "getArticle",
        summary: 'Get an article by UUID',
        tags: ["Article"],
        parameters: [
            new OA\Parameter(
                name: "uuid",
                description: "Article UUID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", default: '123e4567-e89b-12d3-a456-426614174000')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "articleId",
                                description: "Article UUID",
                                type: "string",
                                example: "123e4567-e89b-12d3-a456-426614174000"
                            ),
                            new OA\Property(
                                property: "title",
                                description: "Article title",
                                type: "string",
                                example: "Lorem ipsum dolor sit amet"
                            ),
                            new OA\Property(
                                property: "comments",
                                description: "Article comments",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(
                                            property: 'id',
                                            description: 'Comment id',
                                            type: "string",
                                            example: "c534e0b8-8a62-4aef-b925-76071a101ea0"
                                        ),
                                        new OA\Property(
                                            property: 'name',
                                            description: 'Comment name',
                                            type: "string",
                                            example: "Lorem Ipsum"
                                        ),
                                        new OA\Property(
                                            property: 'email',
                                            description: 'Comment email',
                                            type: "string",
                                            example: "example@email.com"
                                        )
                                    ]
                                )
                            ),
                        ],
                        type: "object"
                    )
                )
            )
        ]
    )]
    public function __invoke(string $uuid): Response
    {
        $query = new GetArticleQuery($uuid);

        $envelope = $this->queryBus->dispatch($query);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $result = $handled->getResult();

        return $this->json($result, Response::HTTP_OK);
    }
}
