<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\AddComment;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\CommentEmail;
use App\Blog\Article\Domain\ValueObject\CommentId;
use App\Blog\Article\Domain\ValueObject\CommentMessage;
use App\Blog\Article\Domain\ValueObject\CommentName;
use Doctrine\ORM\EntityManagerInterface;

/** @psalm-suppress UnusedClass */
final class AddCommentHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ArticleService $articleService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(AddCommentCommand $addCommentCommand): void
    {
        $article = $this->articleService->find(new ArticleId($addCommentCommand->articleId));

        if (null === $article) {
            throw new \DomainException('Article not found');
        }

        $comment = new Comment(
            CommentId::generate(),
            new CommentName($addCommentCommand->name),
            new CommentEmail($addCommentCommand->email),
            new CommentMessage($addCommentCommand->message),
            new \DateTimeImmutable()
        );

        try {
            $this->entityManager->beginTransaction();
            $article->addComment($comment);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            // TODO Add log
            throw $exception;
        }
    }
}
