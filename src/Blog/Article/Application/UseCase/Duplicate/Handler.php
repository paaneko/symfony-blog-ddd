<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Duplicate;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
        private ArticleService $articleService
    ) {
    }

    public function handle(Command $command): ArticleId
    {
        $articleId = new ArticleId($command->articleId);
        $article = $this->articleService->find($articleId);

        if (null === $article) {
            throw new \DomainException('Article not found');
        }

        $duplication = $article->duplicate();

        try {
            $this->entityManager->beginTransaction();
            $this->articleService->add($duplication);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            // TODO add log
            throw $exception;
        }

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $duplication->getId();
    }
}
