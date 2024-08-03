<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Transformer;

use App\Auth\User\Domain\Entity\User;
use App\Blog\Article\Application\Dto\ArticleAuthorDto;

final class ArticleUserTransformer
{
    public function fromUser(User $user): ArticleAuthorDto
    {
        return new ArticleAuthorDto(
            id: $user->getId()->getValue(),
            name: $user->getName()->getValue(),
            email: $user->getEmail()->getValue()
        );
    }
}
