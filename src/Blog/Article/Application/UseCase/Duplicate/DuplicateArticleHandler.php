<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand;
use App\Blog\Article\Domain\Exception\ArticleNotFoundException;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class DuplicateArticleHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private MessageBusInterface $commandBus,
        private ArticleService $articleService,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(DuplicateArticleCommand $command): void
    {
        $articleId = new ArticleId($command->articleId);
        $article = $this->articleService->find($articleId);

        if (null === $article) {
            throw new ArticleNotFoundException();
        }

        $createArticleCommand = new CreateArticleCommand(
            $article->getTitle()->getValue(),
            $article->getContent()->getValue(),
            $article->getCategoryId()->getValue(),
            $article->getSectionId()?->getValue(),
            $article->getAuthorId()->getValue(),
            $article->getMainImageId()->getValue()
        );

        $this->logger->info('Article duplicated command successfully created', [
            'command' => $command,
        ]);

        $this->commandBus->dispatch($createArticleCommand);
    }
}
