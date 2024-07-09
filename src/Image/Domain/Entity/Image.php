<?php

declare(strict_types=1);

namespace App\Image\Domain\Entity;

use App\Image\Domain\ValueObject\Name;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'images')]
class Image extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 255)]
    private Id $id;

    #[ORM\Embedded]
    private Name $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isUsed;

    public function __construct(Id $id, Name $name, bool $isUsed)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isUsed = $isUsed;
    }

    public function setUsed(): void
    {
        $this->isUsed = true;
    }

    public function __toString(): string
    {
        return $this->getId() . '/' . $this->getName()->getValue();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function isUsed(): bool
    {
        return $this->isUsed;
    }
}
