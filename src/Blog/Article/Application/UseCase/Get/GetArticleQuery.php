<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Get;

use App\SharedKernel\Domain\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetArticleQuery implements QueryInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $articleId;

    public function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }
}
