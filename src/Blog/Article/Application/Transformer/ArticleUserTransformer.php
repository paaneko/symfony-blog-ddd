<?php

namespace App\Blog\Article\Application\Transformer;

use App\Auth\User\Domain\Entity\User;
use App\Blog\Article\Application\Dto\ArticleAuthorDto;

class ArticleUserTransformer
{
    public function fromUser(User $user): ArticleAuthorDto
    {
        return new ArticleAuthorDto(
            id: $user->getId(),
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue()
        );
    }
}