<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\Event\OnArticleAddRequestedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class AddArticleController extends AbstractController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/article', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $articleAddRequestedEvent = new OnArticleAddRequestedEvent(
            /* @phpstan-ignore-next-line */
            $parameters['title'],
            /* @phpstan-ignore-next-line */
            $parameters['content'],
            /* @phpstan-ignore-next-line */
            $parameters['categoryId'],
            /* @phpstan-ignore-next-line */
            $parameters['sectionId'] ?? null,
            /* @phpstan-ignore-next-line */
            $parameters['authorId'],
            /* @phpstan-ignore-next-line */
            $parameters['imageId'],
        );

        $errors = $this->validator->validate($articleAddRequestedEvent);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->eventDispatcher->dispatch($articleAddRequestedEvent);

        return $this->json(null, Response::HTTP_CREATED);
    }
}
