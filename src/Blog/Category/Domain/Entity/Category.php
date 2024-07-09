<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\Entity;

use App\Blog\Category\Domain\ValueObject\Name;
use App\Blog\Category\Domain\ValueObject\Slug;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('categories')]
class Category extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME)]
    private Id $id;

    #[ORM\Embedded(columnPrefix: false)]
    private Name $name;

    #[ORM\Embedded(columnPrefix: false)]
    private Slug $slug;

    public function __construct(Id $id, Name $name, Slug $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): Id
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): Name
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getSlug(): Slug
    {
        return $this->slug;
    }
}
