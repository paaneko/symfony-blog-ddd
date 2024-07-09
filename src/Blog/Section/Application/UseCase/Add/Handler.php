<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\UseCase\Add;

use App\Blog\Section\Application\Service\SectionService;
use App\Blog\Section\Domain\Entity\Id;
use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SectionService $sectionService
    ) {
    }

    public function handle(Command $addSectionCommand): Id
    {
        $section = new Section(
            $sectionId = Id::generate(),
            new Name($addSectionCommand->name)
        );

        $this->sectionService->add($section);

        $this->entityManager->flush();

        return $sectionId;
    }
}
