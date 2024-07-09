<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Add\Command;
use App\Auth\User\Application\UseCase\Add\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class AddUserController extends AbstractController
{
    #[Route('user', methods: ['POST'])]
    public function __invoke(Request $request, Handler $handler, ValidatorInterface $validator): Response
    {
        $parameters = json_decode($request->getContent(), true);

        /** @phpstan-ignore-next-line */
        $command = new Command($parameters['name'], $parameters['email']);

        $validator->validate($command);

        $responseData = $handler->handle($command);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
