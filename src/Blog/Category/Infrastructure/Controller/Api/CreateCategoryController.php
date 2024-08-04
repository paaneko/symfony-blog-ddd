<?php

declare(strict_types=1);

namespace App\Blog\Category\Infrastructure\Controller\Api;

use App\Blog\Category\Application\UseCase\Create\CreateCategoryCommand;
use App\Blog\Category\Application\UseCase\Create\CreateCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

/** @psalm-suppress UnusedClass */
final class CreateCategoryController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/category', name: "create_category", methods: ['POST'])]
    #[OA\Post(
        path: "/category",
        operationId: "createCategory",
        summary: "Create category",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "slug", type: "string"),
                ],
                type: "object"
            )
        ),
        tags: ["Article - Category"],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: "Successful response",
            )
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

        $command = new CreateCategoryCommand(
            $parameters['name'],
            $parameters['slug']
        );

        $this->commandBus->dispatch($command);

        return new Response(status: Response::HTTP_CREATED);
    }
}
