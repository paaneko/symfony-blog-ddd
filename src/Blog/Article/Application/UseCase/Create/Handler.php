<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Create;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use App\Blog\Article\Domain\ValueObject\ArticleContent;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\ArticleMainImageId;
use App\Blog\Article\Domain\ValueObject\ArticleTitle;
use App\Blog\Article\Domain\ValueObject\ArticleViews;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\SectionId;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/** @psalm-suppress UnusedClass */
#[AsMessageHandler]
class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
        private ArticleService $articleService,
    ) {
    }

    public function __invoke(Command $createArticleCommand): void
    {
        $sectionId = $createArticleCommand->sectionId;

        $article = new Article(
            ArticleId::generate(),
            new ArticleTitle($createArticleCommand->title),
            new ArticleContent($createArticleCommand->content),
            new CategoryId($createArticleCommand->categoryId),
            ($sectionId) ? new SectionId($sectionId) : null,
            new ArticleAuthorId($createArticleCommand->authorId),
            new ArticleMainImageId($createArticleCommand->imageId),
            ArticleViews::init(),
            new \DateTimeImmutable()
        );

        try {
            $this->entityManager->beginTransaction();
            $this->articleService->add($article);
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
    }
}
