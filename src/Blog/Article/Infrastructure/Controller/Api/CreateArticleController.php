<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class CreateArticleController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    #[Route('/article', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $createArticleCommand = new CreateArticleCommand(
            $parameters['title'],
            $parameters['content'],
            $parameters['categoryId'],
            $parameters['sectionId'] ?? null,
            $parameters['authorId'],
            $parameters['imageId'],
        );

        $this->commandBus->dispatch($createArticleCommand);

        return $this->json('', Response::HTTP_CREATED);
    }
}
