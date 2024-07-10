<?php

namespace App\Search\Blog\Application\UseCase\AddIndex;

use App\Search\Blog\Application\Service\IndexService;
use App\Search\Blog\Domain\Entity\Id;
use App\Search\Blog\Domain\Entity\Index;
use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private IndexService $indexService
    ) {
    }

    public function __invoke(Command $addIndexCommand): void
    {
        $index = new Index(
            Id::generate(),
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