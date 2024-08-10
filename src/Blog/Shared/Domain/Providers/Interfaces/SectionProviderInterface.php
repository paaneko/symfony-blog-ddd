<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers\Interfaces;

use App\Blog\Shared\Application\Dto\SectionDto;

interface SectionProviderInterface
{
    public function getById(string $id): SectionDto;
}
