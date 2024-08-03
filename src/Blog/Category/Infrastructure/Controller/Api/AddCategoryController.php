<?php

declare(strict_types=1);

namespace App\Blog\Category\Infrastructure\Controller\Api;

use App\Blog\Category\Application\UseCase\Add\Command;
use App\Blog\Category\Application\UseCase\Add\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class AddCategoryController extends AbstractController
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    #[Route('/category', methods: ['POST'])]
    public function __invoke(Request $request, Handler $handler): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $addCategoryCommand = new Command(
            /* @phpstan-ignore-next-line */
            $parameters['name'],
            /* @phpstan-ignore-next-line */
            $parameters['slug']
        );

        $errors = $this->validator->validate($addCategoryCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $responseData = $handler->handle($addCategoryCommand);

        return $this->json($responseData, Response::HTTP_CREATED);
    }
}
