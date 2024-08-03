<?php

namespace App\Blog\Article\Infrastructure\Repository;

use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Blog\Article\Application\Dto\ArticleAuthorDto;
use App\Blog\Article\Application\Repository\ArticleAuthorRepositoryInterface;
use App\Blog\Article\Application\Transformer\ArticleUserTransformer;
use App\Blog\Article\Domain\Exception\ArticleAuthorNotFoundException;
use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;

/** Provide Repository Collection from external BC's */
class PostgresArticleAuthorRepository implements ArticleAuthorRepositoryInterface
{
    public function __construct(
        private UserService $userService,
        private ArticleUserTransformer $articleUserTransformer
    )
    {
    }

    public function getById(string $id): ArticleAuthorDto
    {
        $user = $this->userService->find(new UserId($id));

        if ($user === null) {
            throw new ArticleAuthorNotFoundException();
        }

        return $this->articleUserTransformer->fromUser($user);
    }
}