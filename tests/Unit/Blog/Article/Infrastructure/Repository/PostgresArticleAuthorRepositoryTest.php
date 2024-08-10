<?php

declare(strict_types=1);

namespace App\Tests\Unit\Blog\Article\Infrastructure\Repository;

use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Blog\Article\Application\Dto\ArticleAuthorDto;
use App\Blog\Article\Application\Transformer\ArticleUserTransformer;
use App\Blog\Article\Domain\Exception\ArticleAuthorNotFoundException;
use App\Blog\Article\Infrastructure\Repository\PostgresArticleAuthorRepository;
use App\Tests\Builder\Blog\Article\Application\Dto\ArticleAuthorDtoBuilder;
use App\Tests\UnitTestCase;

final class PostgresArticleAuthorRepositoryTest extends UnitTestCase
{
    private ArticleAuthorDto $authorDto;
    private UserService $userService;
    private ArticleUserTransformer $articleUserTransformer;
    private PostgresArticleAuthorRepository $postgresArticleAuthorRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorDto = (new ArticleAuthorDtoBuilder())->build();

        $this->userService = $this->createMock(UserService::class);
        $this->articleUserTransformer = $this->createMock(ArticleUserTransformer::class);
        $this->postgresArticleAuthorRepository = new PostgresArticleAuthorRepository($this->userService, $this->articleUserTransformer);
    }

    public function testGetByIdShouldThrowArticleAuthorNotFoundExceptionWhenUserDoesNotExist(): void
    {
        $notExistentUserId = $this->faker->uuid();

        $this->userService->expects($this->once())
            ->method('find')
            ->with(new UserId($notExistentUserId))
            ->willReturn(null);

        $this->expectException(ArticleAuthorNotFoundException::class);
        $this->postgresArticleAuthorRepository->getById($notExistentUserId);
    }

    public function testGetByIdShouldReturnCorrectAuthorDtoWhenUserExists(): void
    {
        $userId = $this->faker->uuid();

        $user = $this->createMock(User::class);
        $this->userService->expects($this->once())
            ->method('find')
            ->with(new UserId($userId))
            ->willReturn($user);

        $this->articleUserTransformer->expects($this->once())
            ->method('fromUser')
            ->with($user)
            ->willReturn($this->authorDto);

        $actualAuthorDto = $this->postgresArticleAuthorRepository->getById($userId);
        $this->assertEquals($this->authorDto, $actualAuthorDto);
    }
}
