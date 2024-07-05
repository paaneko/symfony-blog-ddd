<?php

declare(strict_types=1);

namespace App\Http\Article;

use App\Article\UseCase\ArticlesFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @psalm-suppress UnusedClass */
class GetArticlesController extends AbstractController
{
    #[Route('/articles', methods: ['GET'])]
    public function __invoke(ArticlesFetcher $articlesFetcher): Response
    {
        return $this->json($articlesFetcher->fetch(), Response::HTTP_OK);
    }
}
