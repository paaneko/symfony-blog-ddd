<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\Exception\SectionNotFoundException;
use App\Blog\Shared\Application\Dto\SectionDto;
use App\Blog\Shared\Application\Transformer\SectionTransformer;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final class SectionProvider implements SectionProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SectionTransformer $sectionTransformer
    ) {
    }

    public function getById(string $id): SectionDto
    {
        $section = $this->entityManager->find(Section::class, $id);

        if (null === $section) {
            throw new SectionNotFoundException();
        }

        return $this->sectionTransformer->fromSection($section);
    }
}
