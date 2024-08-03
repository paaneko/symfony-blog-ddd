<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\UseCase\Create;

use App\Blog\Section\Application\Service\SectionService;
use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\ValueObject\SectionId;
use App\Blog\Section\Domain\ValueObject\SectionName;
use Doctrine\ORM\EntityManagerInterface;

final class CreateSectionHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SectionService $sectionService
    ) {
    }

    public function handle(CreateSectionCommand $addSectionCommand): SectionId
    {
        $section = new Section(
            $sectionId = SectionId::generate(),
            new SectionName($addSectionCommand->name)
        );

        $this->sectionService->add($section);

        $this->entityManager->flush();

        return $sectionId;
    }
}
