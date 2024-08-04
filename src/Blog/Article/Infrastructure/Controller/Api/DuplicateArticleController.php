<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\Duplicate\DuplicateArticleCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/** @psalm-suppress UnusedClass */
final class DuplicateArticleController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/article/duplicate', name: 'article_duplicate', methods: ['POST'])]
    #[OA\Post(
        path: '/article/duplicate',
        operationId: 'articleDuplicate',
        summary: 'Duplicate article',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['articleId'],
                properties: [
                    new OA\Property(property: 'articleId', type: 'string'),
                ]
            )
        ),
        tags: ['Article'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response'
            ),
        ]
    )]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $command = new DuplicateArticleCommand(
            $parameters['articleId']
        );

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
