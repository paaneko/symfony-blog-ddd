<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\Service;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\Repository\SectionRepositoryInterface;

class SectionService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private SectionRepositoryInterface $sectionRepository)
    {
    }

    public function add(Section $section): void
    {
        $this->sectionRepository->add($section);
    }
}
