<?php

declare(strict_types=1);

namespace App\Tests\Unit\Blog\Article\Application\UseCase\Create;

use App\Blog\Article\Application\Dto\ArticleAuthorDto;
use App\Blog\Article\Application\Dto\ArticleMainImageDto;
use App\Blog\Article\Application\Repository\ArticleAuthorRepositoryInterface;
use App\Blog\Article\Application\Repository\ArticleMainImageRepositoryInterface;
use App\Blog\Article\Application\Service\ArticleService;
use App\Blog\Article\Application\UseCase\Create\CreateArticleCommand;
use App\Blog\Article\Application\UseCase\Create\CreateArticleHandler;
use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Shared\Application\Dto\CategoryDto;
use App\Blog\Shared\Application\Dto\SectionDto;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryProviderInterface;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionProviderInterface;
use App\Tests\Builder\Blog\Article\Application\Dto\ArticleAuthorDtoBuilder;
use App\Tests\Builder\Blog\Article\Application\Dto\ArticleMainImageDtoBuilder;
use App\Tests\Builder\Blog\Article\Domain\Entity\ArticleBuilder;
use App\Tests\Builder\Blog\Article\Domain\Event\ArticleCreatedEventBuilder;
use App\Tests\Builder\Blog\Shared\Application\CategoryDtoBuilder;
use App\Tests\Builder\Blog\Shared\Application\SectionDtoBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

final class CreateArticleHandlerTest extends UnitTestCase
{
    private Article $fakeArticle;
    private ArticleAuthorRepositoryInterface $articleAuthorRepository;
    private ArticleMainImageRepositoryInterface $articleMainImageRepository;
    private CategoryProviderInterface $categoryProvider;
    private SectionProviderInterface $sectionProvider;
    private EntityManagerInterface $entityManager;
    private ArticleService $articleService;
    private MessageBusInterface $eventBus;
    private UuidFactory $uuidFactory;
    private ClockInterface $clock;
    private LoggerInterface $logger;

    private ArticleAuthorDto $articleAuthorDto;
    private ArticleMainImageDto $articleMainImageDto;
    private CategoryDto $categoryDto;
    private SectionDto $sectionDto;
    private CreateArticleCommand $createArticleCommand;
    private CreateArticleHandler $createArticleHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeArticle = (new ArticleBuilder())->build();

        $this->articleAuthorRepository = $this->createMock(ArticleAuthorRepositoryInterface::class);
        $this->articleMainImageRepository = $this->createMock(ArticleMainImageRepositoryInterface::class);
        $this->categoryProvider = $this->createMock(CategoryProviderInterface::class);
        $this->sectionProvider = $this->createMock(SectionProviderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->articleService = $this->createMock(ArticleService::class);
        $this->eventBus = $this->createMock(MessageBusInterface::class);
        $this->uuidFactory = $this->createStub(UuidFactory::class);
        $this->clock = $this->createStub(ClockInterface::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->articleAuthorDto = (new ArticleAuthorDtoBuilder())->fromArticle($this->fakeArticle)->build();
        $this->articleMainImageDto = (new ArticleMainImageDtoBuilder())->fromArticle($this->fakeArticle)->build();
        $this->categoryDto = (new CategoryDtoBuilder())->fromArticle($this->fakeArticle)->build();
        $this->sectionDto = (new SectionDtoBuilder())->fromArticle($this->fakeArticle)->build();

        $this->createArticleCommand = new CreateArticleCommand(
            $this->fakeArticle->getTitle()->getValue(),
            $this->fakeArticle->getContent()->getValue(),
            $this->fakeArticle->getCategoryId()->getValue(),
            $this->fakeArticle->getSectionId()->getValue(),
            $this->fakeArticle->getAuthorId()->getValue(),
            $this->fakeArticle->getMainImageId()->getValue()
        );

        $this->createArticleHandler = new CreateArticleHandler(
            $this->eventBus,
            $this->entityManager,
            $this->articleService,
            $this->articleAuthorRepository,
            $this->articleMainImageRepository,
            $this->categoryProvider,
            $this->sectionProvider,
            $this->uuidFactory,
            $this->clock,
            $this->logger
        );
    }

    public function testCanCreateArticle(): void
    {
        $this->uuidFactory->method('create')->willReturn(new Uuid($this->fakeArticle->getId()->getValue()));
        $this->clock->method('now')->willReturn($this->fakeArticle->getDateTime());
        $articleCreatedEvent = (new ArticleCreatedEventBuilder())->fromArticle($this->fakeArticle)->build();

        $this->articleAuthorRepository->method('getById')->willReturn($this->articleAuthorDto);
        $this->articleMainImageRepository->method('getById')->willReturn($this->articleMainImageDto);
        $this->categoryProvider->method('getById')->willReturn($this->categoryDto);
        $this->sectionProvider->method('getById')->willReturn($this->sectionDto);
        $this->entityManager->expects($this->once())->method('beginTransaction');
        $this->articleService->expects($this->once())->method('add')
            ->with($this->equalTo($this->fakeArticle));
        $this->entityManager->expects($this->once())->method('flush');
        $this->entityManager->expects($this->once())->method('flush');
        $this->entityManager->expects($this->once())->method('commit');
        $this->entityManager->expects($this->never())->method('rollback');

        $this->eventBus->expects($this->once())->method('dispatch')
            ->with($this->equalTo($articleCreatedEvent));

        $this->createArticleHandler->__invoke($this->createArticleCommand);
    }

    public function testCreateArticleHandlerRollsBackTransactionOnException(): void
    {
        $this->uuidFactory->method('create')->willReturn(new Uuid($this->fakeArticle->getId()->getValue()));
        $this->clock->method('now')->willReturn($this->fakeArticle->getDateTime());
        $this->articleAuthorRepository->method('getById')->willReturn($this->articleAuthorDto);
        $this->articleMainImageRepository->method('getById')->willReturn($this->articleMainImageDto);
        $this->categoryProvider->method('getById')->willReturn($this->categoryDto);
        $this->sectionProvider->method('getById')->willReturn($this->sectionDto);
        $this->articleService->method('add')
            ->with($this->equalTo($this->fakeArticle))
            ->willThrowException(new \Exception('Exception while adding article'));

        $this->entityManager->expects($this->once())->method('beginTransaction');
        $this->entityManager->expects($this->never())->method('flush');
        $this->entityManager->expects($this->never())->method('commit');
        $this->entityManager->expects($this->once())->method('rollback');
        $this->eventBus->expects($this->never())->method('dispatch');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Exception while adding article');

        $this->createArticleHandler->__invoke($this->createArticleCommand);
    }
}
