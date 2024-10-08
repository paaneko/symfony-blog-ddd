<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Get;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetArticleFetcher
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ArticleService $articleService
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function __invoke(GetArticleQuery $getArticleQuery): array
    {
        $articleId = new ArticleId($getArticleQuery->articleId);

        $article = $this->articleService->find($articleId);

        if (null === $article) {
            throw new \DomainException('Article not found');
        }

        return [
            'articleId' => $article->getId()->getValue(),
            'title' => $article->getTitle()->getValue(),
            'comments' => $article->getComments()->map(fn (Comment $comment) => [
                'id' => $comment->getId()->getValue(),
                'name' => $comment->getName()->getValue(),
                'email' => $comment->getEmail()->getValue(),
            ]),
        ];
    }
}
