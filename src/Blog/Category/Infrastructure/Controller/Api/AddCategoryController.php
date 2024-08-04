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

/** @psalm-suppress UnusedClass */
final class AddCategoryController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/category', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $addCategoryCommand = new CreateCategoryCommand(
            $parameters['name'],
            $parameters['slug']
        );

        $this->commandBus->dispatch($addCategoryCommand);

        return $this->json('', Response::HTTP_CREATED);
    }
}
