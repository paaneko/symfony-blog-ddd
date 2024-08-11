<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\ConfirmJoin\ConfirmJoinCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ConfirmJoinController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    #[Route('user/{uuid}/confirm-join/{token}', name: 'confirm_join', methods: ['POST'])]
    #[OA\Post(
        path: '/user/{uuid}/confirm-join/{token}',
        operationId: 'confirmJoin',
        summary: 'Confirm user registration',
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'uuid',
                description: 'User UUID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', default: '123e4567-e89b-12d3-a456-426614174000')
            ),
            new OA\Parameter(
                name: 'token',
                description: 'Token value',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', default: '123e4567-e89b-12d3-a456-426614174000')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
            ),
        ]
    )]
    public function __invoke(Request $request, string $uuid, string $token): Response
    {
        $confirmJoinCommand = new ConfirmJoinCommand(
            $uuid,
            $token
        );

        $this->commandBus->dispatch($confirmJoinCommand);

        return new Response(status: Response::HTTP_OK);
    }
}
