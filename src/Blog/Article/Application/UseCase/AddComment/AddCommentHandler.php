<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\AddComment;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\Exception\ArticleNotFoundException;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\CommentEmail;
use App\Blog\Article\Domain\ValueObject\CommentId;
use App\Blog\Article\Domain\ValueObject\CommentMessage;
use App\Blog\Article\Domain\ValueObject\CommentName;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddCommentHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ArticleService $articleService,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(AddCommentCommand $addCommentCommand): void
    {
        $article = $this->articleService->find(new ArticleId($addCommentCommand->articleId));

        if (null === $article) {
            throw new ArticleNotFoundException();
        }

        $comment = new Comment(
            CommentId::generate(),
            new CommentName($addCommentCommand->name),
            new CommentEmail($addCommentCommand->email),
            new CommentMessage($addCommentCommand->message),
            $this->clock->now()
        );

        try {
            $this->entityManager->beginTransaction();
            $article->addComment($comment);
            $this->entityManager->flush();
            $this->entityManager->commit();
            $this->logger->info('Comment added successfully', [
                'commentId' => $comment->getId(),
                'command' => $addCommentCommand,
            ]);
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            $this->logger->error('Failed to add comment', [
                'comment' => $comment,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }
}
