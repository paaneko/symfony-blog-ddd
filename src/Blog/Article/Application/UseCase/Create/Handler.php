<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\UseCase\Create;

use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Id;
use App\Blog\Article\Domain\ValueObject\AuthorId;
use App\Blog\Article\Domain\ValueObject\Content;
use App\Blog\Article\Domain\ValueObject\MainImageId;
use App\Blog\Article\Domain\ValueObject\Title;
use App\Blog\Article\Domain\ValueObject\Views;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\SectionId;
use App\Blog\Shared\Domain\Providers\SectionIdProvider;
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
        // Code Smells
        $sectionId = $createArticleCommand->sectionId;

        $article = new Article(
            Id::generate(),
            new Title($createArticleCommand->title),
            new Content($createArticleCommand->content),
            new CategoryId($createArticleCommand->categoryId),
            // TODO Code Smells
            ($sectionId) ? new SectionId($sectionId) : null,
            new AuthorId($createArticleCommand->authorId),
            new MainImageId($createArticleCommand->imageId),
            Views::init(),
            new \DateTimeImmutable()
        );
        /*
         * TODO питання, де краще перевіряти чи існує категорія, або секція в бд?
         * В середині транзакції чи перед транзакцією
         * @see OnUserVerifiedEventSubscriber::createArticle()
         * @see CategoryIdProvider
         * @see SectionIdProvider
         */
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
