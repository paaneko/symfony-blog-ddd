<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Controller\Api;

use App\Auth\User\Application\UseCase\Get\Fetcher;
use App\Auth\User\Application\UseCase\Get\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class GetUserController extends AbstractController
{
    #[Route('/user/{uuid}', methods: ['GET'])]
    public function __invoke(string $uuid, Fetcher $fetcher, ValidatorInterface $validator): Response
    {
        $query = new Query($uuid);

        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            return $this->json($errors);
        }

        return $this->json($fetcher->fetch($query));
    }
}
