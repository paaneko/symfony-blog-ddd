<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Section\Domain\Repository\SectionRepositoryInterface;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionIdProviderInterface;

/** @psalm-suppress UnusedClass */
class SectionIdProvider implements SectionIdProviderInterface
{
    public function __construct(private SectionRepositoryInterface $sectionRepository)
    {
    }

    public function byId(string $sectionId): string
    {
        /**
         * @phpstan-ignore-next-line
         */
        $section = $this->sectionRepository->find($sectionId);

        if (null === $section) {
            throw new \DomainException('Section not found');
        }

        return $sectionId;
    }
}
