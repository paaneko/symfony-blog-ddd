<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Controller\Api;

use App\Blog\Article\Application\UseCase\AddComment\AddCommentCommand;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/** @psalm-suppress UnusedClass */
final class AddCommentController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/article/comment', name: 'add_article_comment', methods: ['POST'])]
    #[OA\Post(
        path: '/article/comment',
        operationId: 'addArticleComment',
        summary: 'Add comment to article',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'articleId', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                ],
                type: 'object'
            )
        ),
        tags: ['Article'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Successful response',
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

        $command = new AddCommentCommand(
            /* @phpstan-ignore-next-line */
            $parameters['articleId'],
            /* @phpstan-ignore-next-line */
            $parameters['name'],
            /* @phpstan-ignore-next-line */
            $parameters['email'],
            /* @phpstan-ignore-next-line */
            $parameters['message']
        );

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
