<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Get\GetArticleFetcher;
use App\Blog\Article\Application\UseCase\Get\GetArticleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class GetArticleController extends AbstractController
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    #[Route('/article/{uuid}', methods: ['GET'])]
    public function __invoke(string $uuid): Response
    {
        $getArticleQuery = new GetArticleQuery($uuid);

        $envelope = $this->queryBus->dispatch($getArticleQuery);

        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $result = $handled->getResult();

        return $this->json($result, Response::HTTP_OK);
    }
}
