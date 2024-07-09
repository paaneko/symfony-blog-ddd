<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers\Interfaces;

interface SectionIdProviderInterface
{
    public function byId(string $sectionId): string;
}
