<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Get;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Query
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $articleId;

    public function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }
}
