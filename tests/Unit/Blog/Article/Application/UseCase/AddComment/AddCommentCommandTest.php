<?php

declare(strict_types=1);

namespace App\Tests\Unit\Blog\Article\Application\UseCase\AddComment;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\AddComment\AddCommentCommand;
use App\Blog\Article\Application\UseCase\AddComment\AddCommentHandler;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\Exception\ArticleNotFoundException;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Tests\Builder\Blog\Article\Domain\Entity\CommentBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

final class AddCommentCommandTest extends UnitTestCase
{
    private Comment $comment;

    private Article $article;
    private ArticleService $articleService;
    private EntityManagerInterface $entityManager;
    private UuidFactory $uuidFactory;
    private ClockInterface $clock;
    private LoggerInterface $logger;

    private AddCommentCommand $addCommentCommand;
    private AddCommentHandler $addCommentHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->comment = (new CommentBuilder())->build();

        $this->article = $this->createMock(Article::class);
        $this->articleService = $this->createMock(ArticleService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->uuidFactory = $this->createStub(UuidFactory::class);
        $this->clock = $this->createMock(ClockInterface::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->addCommentCommand = new AddCommentCommand(
            $this->comment->getArticleId()->getValue(),
            $this->comment->getName()->getValue(),
            $this->comment->getEmail()->getValue(),
            $this->comment->getMessage()->getValue()
        );

        $this->addCommentHandler = new AddCommentHandler(
            $this->articleService,
            $this->entityManager,
            $this->uuidFactory,
            $this->clock,
            $this->logger
        );
    }

    public function testCanAddComment(): void
    {
        $this->articleService->expects($this->once())
            ->method('find')
            ->with(new ArticleId($this->addCommentCommand->articleId))
            ->willReturn($this->article);

        $this->article->expects($this->once())
            ->method('createComment')
            ->willReturn($this->comment);

        $this->entityManager->expects($this->once())
            ->method('beginTransaction');

        $this->article->expects($this->once())
            ->method('addComment')
            ->with($this->comment);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->entityManager->expects($this->once())
            ->method('commit');

        $this->addCommentHandler->__invoke($this->addCommentCommand);
    }

    public function testCanThrowArticleNotFoundException(): void
    {
        $this->articleService->expects($this->once())
            ->method('find')
            ->with(new ArticleId($this->addCommentCommand->articleId))
            ->willReturn(null);

        $this->expectException(ArticleNotFoundException::class);
        $this->addCommentHandler->__invoke($this->addCommentCommand);
    }

    public function testAddCommentHandlerRollsBackTransactionOnException(): void
    {
        $this->articleService->expects($this->once())
            ->method('find')
            ->with(new ArticleId($this->addCommentCommand->articleId))
            ->willReturn($this->article);

        $this->article->expects($this->once())
            ->method('createComment')
            ->willReturn($this->comment);

        $this->entityManager->expects($this->once())
            ->method('beginTransaction');

        $this->article->expects($this->once())
            ->method('addComment')
            ->with($this->comment)
            ->willThrowException(new \Exception('Exception while adding comment to article'));

        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->entityManager->expects($this->never())
            ->method('commit');

        $this->entityManager->expects($this->once())
            ->method('rollback');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Exception while adding comment to article');

        $this->addCommentHandler->__invoke($this->addCommentCommand);
    }
}
