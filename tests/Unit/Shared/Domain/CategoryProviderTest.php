<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\Exception\CategoryNotFoundException;
use App\Blog\Shared\Application\Dto\CategoryDto;
use App\Blog\Shared\Application\Transformer\CategoryTransformer;
use App\Blog\Shared\Domain\Providers\CategoryProvider;
use App\Tests\Builder\Blog\Category\Domain\Entity\CategoryBuilder;
use App\Tests\Builder\Blog\Shared\Application\CategoryDtoBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryProviderTest extends UnitTestCase
{
    private Category $category;
    private CategoryDto $categoryDto;

    private EntityManagerInterface $entityManager;
    private CategoryTransformer $categoryTransformer;
    private CategoryProvider $categoryProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = (new CategoryBuilder())->build();
        $this->categoryDto = (new CategoryDtoBuilder())->fromCategory($this->category)->build();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->categoryTransformer = $this->createMock(CategoryTransformer::class);
        $this->categoryProvider = new CategoryProvider($this->entityManager, $this->categoryTransformer);
    }

    public function testShouldReturnCategoryDtoWithCorrectPropertiesWhenCategoryExists(): void
    {
        $categoryId = 'existing-category-id';

        $this->entityManager->method('find')->with(Category::class, $categoryId)->willReturn($this->category);
        $this->categoryTransformer->method('fromCategory')->with($this->category)->willReturn($this->categoryDto);

        $actualCategoryDto = $this->categoryProvider->getById($categoryId);

        $this->assertEquals($this->categoryDto, $actualCategoryDto);
    }

    public function testShouldThrowCategoryNotFoundExceptionWhenCategoryDoesNotExist(): void
    {
        $categoryId = 'non-existent-category-id';

        $this->entityManager->method('find')->with(Category::class, $categoryId)->willReturn(null);

        $this->expectException(CategoryNotFoundException::class);

        $this->categoryProvider->getById($categoryId);
    }
}
