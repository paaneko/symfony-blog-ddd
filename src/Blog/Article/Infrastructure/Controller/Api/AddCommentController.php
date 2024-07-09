<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\AddComment\Command;
use App\Blog\Article\Application\UseCase\AddComment\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class AddCommentController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Route('/comment', methods: ['POST'])]
    public function __invoke(Request $request, Handler $handler): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $addCommentCommand = new Command(
            /* @phpstan-ignore-next-line */
            $parameters['articleId'],
            /* @phpstan-ignore-next-line */
            $parameters['name'],
            /* @phpstan-ignore-next-line */
            $parameters['email'],
            /* @phpstan-ignore-next-line */
            $parameters['message']
        );

        $errors = $this->validator->validate($addCommentCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $handler->handle($addCommentCommand);

        return $this->json(null, Response::HTTP_CREATED);
    }
}
