<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Create\CreateUserCommand;
use App\Auth\User\Application\UseCase\Create\CreateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class AddUserController extends AbstractController
{
    #[Route('user', methods: ['POST'])]
    public function __invoke(Request $request, CreateUserHandler $handler, ValidatorInterface $validator): Response
    {
        $parameters = json_decode($request->getContent(), true);

        /** @phpstan-ignore-next-line */
        $command = new CreateUserCommand($parameters['name'], $parameters['email']);

        $validator->validate($command);

        $responseData = $handler->handle($command);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
