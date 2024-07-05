<?php

declare(strict_types=1);

namespace App\Http\Article;

use App\Article\UseCase\AddArticleCommand;
use App\Article\UseCase\AddArticleHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @psalm-suppress UnusedClass */
class AddArticleController extends AbstractController
{
    #[Route('/article', methods: ['POST'])]
    public function __invoke(Request $request, AddArticleHandler $addArticleHandler, ValidatorInterface $validator): Response
    {
        /** @var array<string, string> $jsonData */
        $jsonData = json_decode($request->getContent(), true);

        $addArticleCommand = new AddArticleCommand(
            $jsonData['title'],
            $jsonData['content'],
            $jsonData['imageId'],
            $jsonData['imageName']
        );

        $errors = $validator->validate($addArticleCommand);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article = $addArticleHandler->handle($addArticleCommand);

        return $this->json($article, Response::HTTP_CREATED);
    }
}
