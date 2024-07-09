<?php

declare(strict_types=1);

namespace App\Blog\Section\Infrastructure\Controller\Api;

use App\Blog\Section\Application\UseCase\Add\Command;
use App\Blog\Section\Application\UseCase\Add\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class AddSectionController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Route('/section', methods: ['POST'])]
    public function __invoke(Request $request, Handler $handler): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $addSectionCommand = new Command(
            /* @phpstan-ignore-next-line */
            $parameters['name']
        );

        $errors = $this->validator->validate($addSectionCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $responseData = $handler->handle($addSectionCommand);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
