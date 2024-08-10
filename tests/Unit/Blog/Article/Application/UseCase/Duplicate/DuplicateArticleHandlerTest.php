<?php

declare(strict_types=1);

namespace App\Tests\Unit\Blog\Article\Application\UseCase\Duplicate;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand;
use App\Blog\Article\Application\UseCase\Duplicate\DuplicateArticleCommand;
use App\Blog\Article\Application\UseCase\Duplicate\DuplicateArticleHandler;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Exception\ArticleNotFoundException;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Tests\Builder\Blog\Article\Domain\Entity\ArticleBuilder;
use App\Tests\UnitTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class DuplicateArticleHandlerTest extends UnitTestCase
{
    private DuplicateArticleHandler $duplicateArticleHandler;
    private MessageBusInterface $messageBus;
    private ArticleService $articleService;
    private LoggerInterface $logger;

    private Article $article;
    private DuplicateArticleCommand $duplicateArticleCommand;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->articleService = $this->createMock(ArticleService::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->duplicateArticleHandler = new DuplicateArticleHandler(
            $this->messageBus,
            $this->articleService,
            $this->logger
        );

        $this->article = (new ArticleBuilder())->build();

        $this->duplicateArticleCommand = new DuplicateArticleCommand(
            $this->article->getId()->getValue()
        );
    }

    public function testCanDuplicateArticle(): void
    {
        $this->articleService->method('find')
            ->with($this->equalTo(new ArticleId($this->article->getId()->getValue())))
            ->willReturn($this->article);

        $createArticleCommand = new CreateArticleCommand(
            $this->article->getTitle()->getValue(),
            $this->article->getContent()->getValue(),
            $this->article->getCategoryId()->getValue(),
            $this->article->getSectionId()?->getValue(),
            $this->article->getAuthorId()->getValue(),
            $this->article->getMainImageId()->getValue()
        );

        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($createArticleCommand);

        $this->duplicateArticleHandler->__invoke($this->duplicateArticleCommand);
    }

    public function testThrowsExceptionWhenArticleNotFound(): void
    {
        $this->articleService->method('find')
            ->with($this->equalTo(new ArticleId($this->duplicateArticleCommand->articleId)))
            ->willReturn(null);

        $this->expectException(ArticleNotFoundException::class);

        $this->duplicateArticleHandler->__invoke($this->duplicateArticleCommand);
    }
}
