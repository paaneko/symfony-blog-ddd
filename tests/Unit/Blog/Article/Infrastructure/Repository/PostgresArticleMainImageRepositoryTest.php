<?php

declare(strict_types=1);

namespace App\Tests\Unit\Blog\Article\Infrastructure\Repository;

use App\Blog\Article\Application\Dto\ArticleMainImageDto;
use App\Blog\Article\Application\Transformer\ArticleMainImageTransformer;
use App\Blog\Article\Domain\Exception\ArticleMainImageNotFoundException;
use App\Blog\Article\Infrastructure\Repository\PostgresArticleMainImageRepository;
use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Image;
use App\Image\Domain\ValueObject\ImageId;
use App\Tests\Builder\Blog\Article\Application\Dto\ArticleMainImageDtoBuilder;
use App\Tests\UnitTestCase;

final class PostgresArticleMainImageRepositoryTest extends UnitTestCase
{
    private ArticleMainImageDto $mainImageDto;
    private ImageService $imageService;
    private ArticleMainImageTransformer $articleMainImageTransformer;
    private PostgresArticleMainImageRepository $postgresArticleMainImageRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mainImageDto = (new ArticleMainImageDtoBuilder())->build();

        $this->imageService = $this->createMock(ImageService::class);
        $this->articleMainImageTransformer = $this->createMock(ArticleMainImageTransformer::class);
        $this->postgresArticleMainImageRepository = new PostgresArticleMainImageRepository($this->imageService, $this->articleMainImageTransformer);
    }

    public function testGetByIdShouldThrowArticleMainImageNotFoundExceptionWhenImageDoesNotExist(): void
    {
        $notExistentImageId = $this->faker->uuid();

        $this->imageService->expects($this->once())
            ->method('find')
            ->with(new ImageId($notExistentImageId))
            ->willReturn(null);

        $this->expectException(ArticleMainImageNotFoundException::class);
        $this->postgresArticleMainImageRepository->getById($notExistentImageId);
    }

    public function testGetByIdShouldReturnCorrectMainImageDtoWhenImageExists()
    {
        $imageId = $this->faker->uuid();
        $expectedMainImageDto = (new ArticleMainImageDtoBuilder())->build();

        $image = $this->createMock(Image::class);
        $this->imageService->expects($this->once())
            ->method('find')
            ->with(new ImageId($imageId))
            ->willReturn($image);

        $this->articleMainImageTransformer->expects($this->once())
            ->method('fromImage')
            ->with($image)
            ->willReturn($expectedMainImageDto);

        $actualMainImageDto = $this->postgresArticleMainImageRepository->getById($imageId);
        $this->assertEquals($expectedMainImageDto, $actualMainImageDto);
    }
}
