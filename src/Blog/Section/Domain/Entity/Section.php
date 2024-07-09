<?php

declare(strict_types=1);

namespace App\Blog\Section\Domain\Entity;

use App\Blog\Section\Domain\ValueObject\Name;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('sections')]
class Section extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 255)]
    private Id $id;

    #[ORM\Embedded(columnPrefix: false)]
    private Name $name;

    public function __construct(Id $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
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
}
