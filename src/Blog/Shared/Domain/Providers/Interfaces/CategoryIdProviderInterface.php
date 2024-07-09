<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers\Interfaces;

interface CategoryIdProviderInterface
{
    public function byId(string $categoryId): string;
}
