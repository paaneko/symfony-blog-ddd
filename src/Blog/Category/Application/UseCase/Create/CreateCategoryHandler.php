<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\UseCase\Create;

use App\Blog\Category\Application\Service\CategoryService;
use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\ValueObject\CategoryId;
use App\Blog\Category\Domain\ValueObject\CategoryName;
use App\Blog\Category\Domain\ValueObject\CategorySlug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateCategoryHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private CategoryService $categoryService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateCategoryCommand $addCategoryCommand): CategoryId
    {
        $category = new Category(
            $categoryId = CategoryId::generate(),
            new CategoryName($addCategoryCommand->name),
            new CategorySlug($addCategoryCommand->slug)
        );

        $this->categoryService->add($category);

        $this->entityManager->flush();

        return $categoryId;
    }
}
