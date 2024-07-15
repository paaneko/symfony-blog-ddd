<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\Create\Command as CreateArticleCommand;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageBusInterface $messageBus,
        private ArticleService $articleService,
    ) {
    }

    public function handle(Command $command): void
    {
        $articleId = new ArticleId($command->articleId);
        $article = $this->articleService->find($articleId);

        if (null === $article) {
            throw new \DomainException('Article not found');
        }

        $createArticleCommand = new CreateArticleCommand(
            $article->getTitle()->getValue(),
            $article->getContent()->getValue(),
            $article->getCategoryId()->getValue(),
            $article->getSectionId()?->getValue(),
            $article->getAuthorId()->getValue(),
            $article->getMainImageId()->getValue()
        );

        $this->messageBus->dispatch($createArticleCommand);
    }
}
