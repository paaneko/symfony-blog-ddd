<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Create;

use App\Blog\Article\Application\Repository\ArticleAuthorRepositoryInterface;
use App\Blog\Article\Application\Repository\ArticleMainImageRepositoryInterface;
use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use App\Blog\Article\Domain\ValueObject\ArticleContent;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\ArticleMainImageId;
use App\Blog\Article\Domain\ValueObject\ArticleTitle;
use App\Blog\Article\Domain\ValueObject\ArticleViews;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\NullableSectionId;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryProviderInterface;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
        private ArticleService $articleService,
        private ArticleAuthorRepositoryInterface $articleAuthorRepository,
        private ArticleMainImageRepositoryInterface $articleMainImageRepository,
        private CategoryProviderInterface $categoryProvider,
        private SectionProviderInterface $sectionProvider,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(Command $createArticleCommand): void
    {
        /** Ensure that all required aggregates exist */
        $articleAuthorDto = $this->articleAuthorRepository->getById($createArticleCommand->authorId);
        $articleMainImageDto = $this->articleMainImageRepository->getById($createArticleCommand->imageId);
        $categoryDto = $this->categoryProvider->getById($createArticleCommand->categoryId);
        $sectionDto = (null === $createArticleCommand->sectionId)
            ? null : $this->sectionProvider->getById($createArticleCommand->sectionId);

        $article = new Article(
            ArticleId::generate(),
            new ArticleTitle($createArticleCommand->title),
            new ArticleContent($createArticleCommand->content),
            new CategoryId($categoryDto->id),
            new NullableSectionId($sectionDto?->id),
            new ArticleAuthorId($articleAuthorDto->id),
            new ArticleMainImageId($articleMainImageDto->id),
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
            // Add logger
            throw $exception;
        }

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
