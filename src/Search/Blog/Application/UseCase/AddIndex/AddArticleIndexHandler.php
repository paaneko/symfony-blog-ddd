<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\UseCase\AddIndex;

use App\Search\Blog\Application\Service\IndexService;
use App\Search\Blog\Domain\Entity\ArticleIndex;
use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use App\Search\Blog\Domain\ValueObject\ArticleIndexId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** @psalm-suppress UnusedClass */
#[AsMessageHandler]
final class AddArticleIndexHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IndexService $indexService
    ) {
    }

    public function __invoke(AddArticleIndexCommand $addIndexCommand): void
    {
        $index = new ArticleIndex(
            ArticleIndexId::generate(),
            new ArticleIdentifier($addIndexCommand->articleIdentifier),
            $addIndexCommand->articleTitle
        );

        try {
            $this->entityManager->beginTransaction();
            $this->indexService->add($index);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            // TODO add log
            throw $exception;
        }
    }
}
