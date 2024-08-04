<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use App\SharedKernel\Domain\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class DuplicateArticleCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $articleId;

    public function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }
}
