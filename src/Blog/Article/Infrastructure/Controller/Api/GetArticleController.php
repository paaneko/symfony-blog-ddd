<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Get\Fetcher;
use App\Blog\Article\Application\UseCase\Get\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class GetArticleController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Route('/article/{uuid}', methods: ['GET'])]
    public function __invoke(string $uuid, Fetcher $fetcher): Response
    {
        $getArticleQuery = new Query($uuid);

        $errors = $this->validator->validate($getArticleQuery);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($fetcher->fetch($getArticleQuery), Response::HTTP_OK);
    }
}
