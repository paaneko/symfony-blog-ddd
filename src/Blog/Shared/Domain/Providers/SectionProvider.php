<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Section\Domain\Exception\SectionNotFoundException;
use App\Blog\Section\Domain\Repository\SectionRepositoryInterface;
use App\Blog\Shared\Application\Dto\SectionDto;
use App\Blog\Shared\Application\Transformer\SectionTransformer;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionProviderInterface;

final class SectionProvider implements SectionProviderInterface
{
    public function __construct(
        private SectionRepositoryInterface $sectionRepository,
        private SectionTransformer $sectionTransformer
    ) {
    }

    public function getById(string $id): SectionDto
    {
        $section = $this->sectionRepository->find($id);

        if (null === $section) {
            throw new SectionNotFoundException();
        }

        return $this->sectionTransformer->fromSection($section);
    }
}
