<?php

declare(strict_types=1);

namespace App\Blog\Section\Domain\Repository;

use App\Blog\Section\Domain\Entity\Section;

interface SectionRepositoryInterface
{
    public function add(Section $section): void;
}
