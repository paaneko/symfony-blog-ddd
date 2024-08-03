<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Duplicate\DuplicateArticleCommand;
use App\Blog\Article\Application\UseCase\Duplicate\DuplicateArticleHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class DuplicateArticleController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Route('/article/duplicate', methods: ['POST'])]
    public function __invoke(Request $request, DuplicateArticleHandler $handler): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $duplicateArticleCommand = new DuplicateArticleCommand(
            /* @phpstan-ignore-next-line */
            $parameters['articleId']
        );

        $errors = $this->validator->validate($duplicateArticleCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $handler->handle($duplicateArticleCommand);

        return $this->json(null, Response::HTTP_CREATED);
    }
}
