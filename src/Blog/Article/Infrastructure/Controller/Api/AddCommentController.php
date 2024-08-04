<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\AddComment\AddCommentCommand;
use App\Blog\Article\Application\UseCase\AddComment\AddCommentHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class AddCommentController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/comment', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $addCommentCommand = new AddCommentCommand(
            /* @phpstan-ignore-next-line */
            $parameters['articleId'],
            /* @phpstan-ignore-next-line */
            $parameters['name'],
            /* @phpstan-ignore-next-line */
            $parameters['email'],
            /* @phpstan-ignore-next-line */
            $parameters['message']
        );

        $this->commandBus->dispatch($addCommentCommand);

        return $this->json('', Response::HTTP_CREATED);
    }
}
