<?php

namespace App\Blog\Shared\Application\Transformer;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Shared\Application\Dto\CategoryDto;
use App\Blog\Shared\Application\Dto\SectionDto;
use App\Blog\Shared\Domain\Entity\ValueObject\NullableSectionId;

class SectionTransformer
{
    public function fromSection(Section $section): SectionDto
    {
        return new SectionDto(
            id: $section->getId()->getValue(),
            name: $section->getName()->getValue()
        );
    }
}