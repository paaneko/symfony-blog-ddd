<?php

declare(strict_types=1);

namespace App\Image\Domain\Entity;

use App\Image\Domain\Type\ImageIdType;
use App\Image\Domain\Type\ImageNameType;
use App\Image\Domain\ValueObject\ImageId;
use App\Image\Domain\ValueObject\ImageName;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'images')]
class Image extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: ImageIdType::NAME, length: 255)]
    private ImageId $id;

    #[ORM\Column(type: ImageNameType::NAME, length: 255)]
    private ImageName $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isUsed;

    public function __construct(ImageId $id, ImageName $name, bool $isUsed)
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

    public function getId(): ImageId
    {
        return $this->id;
    }

    public function getName(): ImageName
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function isUsed(): bool
    {
        return $this->isUsed;
    }
}
