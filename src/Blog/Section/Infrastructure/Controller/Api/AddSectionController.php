<?php

declare(strict_types=1);

namespace App\Blog\Section\Infrastructure\Controller\Api;

use App\Blog\Section\Application\UseCase\Create\CreateSectionCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

/** @psalm-suppress UnusedClass */
final class AddSectionController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    #[Route('/section', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $addSectionCommand = new CreateSectionCommand(
            /* @phpstan-ignore-next-line */
            $parameters['name']
        );

        $this->commandBus->dispatch($addSectionCommand);

        return $this->json('', Response::HTTP_CREATED);
    }
}
