<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Get\GetUserFetcher;
use App\Auth\User\Application\UseCase\Get\GetUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
final class GetUserController extends AbstractController
{
    #[Route('/user/{uuid}', methods: ['GET'])]
    public function __invoke(string $uuid, GetUserFetcher $fetcher, ValidatorInterface $validator): Response
    {
        $query = new GetUserQuery($uuid);

        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            return $this->json($errors);
        }

        return $this->json($fetcher->fetch($query));
    }
}
