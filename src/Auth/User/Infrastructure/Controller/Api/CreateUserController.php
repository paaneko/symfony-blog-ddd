<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Create\CreateUserCommand;
use App\Auth\User\Application\UseCase\Create\CreateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

/** @psalm-suppress UnusedClass */
final class CreateUserController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('user', name: "create_user", methods: ['POST'])]
    #[OA\Post(
        path: "/user",
        operationId: "createUser",
        summary: "Create user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                ],
                type: "object"
            )
        ),
        tags: ["User"],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: "Successful response",
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

        $command = new CreateUserCommand($parameters['name'], $parameters['email']);

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
