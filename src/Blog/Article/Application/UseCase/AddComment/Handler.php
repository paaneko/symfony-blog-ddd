<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\AddComment;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\Entity\CommentId;
use App\Blog\Article\Domain\Entity\Id;
use App\Blog\Article\Domain\ValueObject\Email;
use App\Blog\Article\Domain\ValueObject\Message;
use App\Blog\Article\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;

/** @psalm-suppress UnusedClass */
class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private ArticleService $articleService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(Command $addCommentCommand): void
    {
        $article = $this->articleService->find(new Id($addCommentCommand->articleId));

        if (null === $article) {
            throw new \DomainException('Article not found');
        }

        $comment = new Comment(
            CommentId::generate(),
            new Name($addCommentCommand->name),
            new Email($addCommentCommand->email),
            new Message($addCommentCommand->message),
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
