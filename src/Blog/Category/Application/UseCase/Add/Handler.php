<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\UseCase\Add;

use App\Blog\Category\Application\Service\CategoryService;
use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\Entity\Id;
use App\Blog\Category\Domain\ValueObject\Name;
use App\Blog\Category\Domain\ValueObject\Slug;
use Doctrine\ORM\EntityManagerInterface;

/** @psalm-suppress UnusedClass */
class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private CategoryService $categoryService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(Command $addCategoryCommand): Id
    {
        $category = new Category(
            $categoryId = Id::generate(),
            new Name($addCategoryCommand->name),
            new Slug($addCategoryCommand->slug)
        );

        $this->categoryService->add($category);

        $this->entityManager->flush();

        return $categoryId;
    }
}
