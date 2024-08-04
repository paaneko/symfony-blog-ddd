<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand as CreateArticleCommand;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\SharedKernel\Domain\Bus\CommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class DuplicateArticleHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageBusInterface $messageBus,
        private ArticleService $articleService,
    ) {
    }

    public function __invoke(DuplicateArticleCommand $command): void
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
