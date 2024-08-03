<?php

declare(strict_types=1);

namespace App\Blog\Shared\Application\Transformer;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Shared\Application\Dto\SectionDto;

final class SectionTransformer
{
    public function fromSection(Section $section): SectionDto
    {
        return new SectionDto(
            id: $section->getId()->getValue(),
            name: $section->getName()->getValue()
        );
    }
}
