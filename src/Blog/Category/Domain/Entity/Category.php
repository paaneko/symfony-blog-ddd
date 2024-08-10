<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\Entity;

use App\Blog\Category\Domain\Type\CategoryIdType;
use App\Blog\Category\Domain\Type\CategoryNameType;
use App\Blog\Category\Domain\Type\CategorySlugType;
use App\Blog\Category\Domain\ValueObject\CategoryId;
use App\Blog\Category\Domain\ValueObject\CategoryName;
use App\Blog\Category\Domain\ValueObject\CategorySlug;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('categories')]
final class Category extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: CategoryIdType::NAME)]
    private CategoryId $id;

    #[ORM\Column(type: CategoryNameType::NAME, length: 255)]
    private CategoryName $name;

    #[ORM\Column(type: CategorySlugType::NAME, length: 255)]
    private CategorySlug $slug;

    public function __construct(CategoryId $id, CategoryName $name, CategorySlug $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): CategoryId
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): CategoryName
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getSlug(): CategorySlug
    {
        return $this->slug;
    }
}
