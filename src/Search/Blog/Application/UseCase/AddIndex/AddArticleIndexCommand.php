<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\UseCase\AddIndex;

use App\SharedKernel\Domain\Bus\CommandInterface;

final class AddArticleIndexCommand implements CommandInterface
{
    public string $articleIdentifier;

    public string $articleTitle;

    public function __construct(string $articleIdentifier, string $articleTitle)
    {
        $this->articleTitle = $articleTitle;
        $this->articleIdentifier = $articleIdentifier;
    }
}
