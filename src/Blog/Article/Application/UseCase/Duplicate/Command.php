<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $articleId;

    public function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }
}
