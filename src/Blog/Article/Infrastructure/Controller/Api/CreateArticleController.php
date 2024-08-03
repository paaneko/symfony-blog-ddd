<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Create\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class CreateArticleController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ValidatorInterface $validator
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

        $createArticleCommand = new Command(
            $parameters['title'],
            $parameters['content'],
            $parameters['categoryId'],
            $parameters['sectionId'] ?? null,
            $parameters['authorId'],
            $parameters['imageId'],
        );

        $errors = $this->validator->validate($createArticleCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->messageBus->dispatch($createArticleCommand);

        return $this->json("", Response::HTTP_CREATED);
    }
}
