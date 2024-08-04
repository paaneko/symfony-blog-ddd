<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Get\GetUserFetcher;
use App\Auth\User\Application\UseCase\Get\GetUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

/** @psalm-suppress UnusedClass */
final class GetUserController extends AbstractController
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    #[Route('/user/{uuid}', name: 'get_user', methods: ['GET'])]
    #[OA\Get(
        path: '/user/{uuid}',
        operationId: "getUser",
        summary: 'Get an article by UUID',
        tags: ["User"],
        parameters: [
            new OA\Parameter(
                name: "uuid",
                description: "User UUID",
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
                                property: "userId",
                                description: "Article UUID",
                                type: "string",
                                example: "123e4567-e89b-12d3-a456-426614174000"
                            ),
                            new OA\Property(
                                property: "name",
                                description: "User name",
                                type: "string",
                                example: "Lorem Ipsum"
                            ),
                            new OA\Property(
                                property: "email",
                                description: "Article comments",
                                type: "string",
                                example: "example@email.com"
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
        $query = new GetUserQuery($uuid);

        $envelope = $this->queryBus->dispatch($query);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $result = $handled->getResult();

        return $this->json($result, Response::HTTP_OK);
    }
}
