<?php

declare(strict_types=1);

namespace App\Blog\Section\Domain\Entity;

use App\Blog\Section\Domain\Type\SectionIdType;
use App\Blog\Section\Domain\Type\SectionNameType;
use App\Blog\Section\Domain\ValueObject\SectionId;
use App\Blog\Section\Domain\ValueObject\SectionName;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('sections')]
class Section extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: SectionIdType::NAME, length: 255)]
    private SectionId $id;

    #[ORM\Column(type: SectionNameType::NAME, length: 255)]
    private SectionName $name;

    public function __construct(SectionId $id, SectionName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): SectionId
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): SectionName
    {
        return $this->name;
    }
}
