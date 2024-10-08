<?php

declare(strict_types=1);

namespace App\Account\Following\Infrastructure\Controller;

use App\Account\Following\Application\UseCase\Follow\FollowCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class FollowController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/follow/{followeeId}', name: 'follow', methods: ['POST'])]
    #[OA\Post(
        path: '/follow/{followeeId}',
        operationId: 'follow',
        summary: 'Follow',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'followerId', type: 'string'),
                ],
                type: 'object'
            )
        ),
        tags: ['Account'],
        parameters: [
            new OA\Parameter(
                name: 'followeeId',
                description: 'Followee UUID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', default: '123e4567-e89b-12d3-a456-426614174000')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response',
            ),
        ]
    )]
    public function index(Request $request, string $followeeId): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $followCommand = new FollowCommand(
            $parameters['followerId'],
            $followeeId
        );

        $this->commandBus->dispatch($followCommand);

        return new Response(status: Response::HTTP_OK);
    }
}
