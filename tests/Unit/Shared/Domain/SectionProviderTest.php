<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\Exception\SectionNotFoundException;
use App\Blog\Shared\Application\Dto\SectionDto;
use App\Blog\Shared\Application\Transformer\SectionTransformer;
use App\Blog\Shared\Domain\Providers\SectionProvider;
use App\Tests\Builder\Blog\Section\Domain\Entity\SectionBuilder;
use App\Tests\Builder\Blog\Shared\Application\SectionDtoBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class SectionProviderTest extends UnitTestCase
{
    private Section $section;
    private SectionDto $sectionDto;

    private EntityManagerInterface $entityManager;
    private SectionTransformer $sectionTransformer;
    private SectionProvider $sectionProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->section = (new SectionBuilder())->build();
        $this->sectionDto = (new SectionDtoBuilder())->fromSection($this->section)->build();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sectionTransformer = $this->createMock(SectionTransformer::class);
        $this->sectionProvider = new SectionProvider($this->entityManager, $this->sectionTransformer);
    }

    public function testShouldReturnSectionDtoWithCorrectPropertiesWhenSectionExists(): void
    {
        $sectionId = 'existing-section-id';

        $this->entityManager->method('find')->with(Section::class, $sectionId)->willReturn($this->section);
        $this->sectionTransformer->method('fromSection')->with($this->section)->willReturn($this->sectionDto);

        $actualSectionDto = $this->sectionProvider->getById($sectionId);

        $this->assertEquals($this->sectionDto, $actualSectionDto);
    }

    public function testShouldThrowSectionNotFoundExceptionWhenSectionDoesNotExist(): void
    {
        $sectionId = 'non-existent-section-id';

        $this->entityManager->method('find')->with(Section::class, $sectionId)->willReturn(null);

        $this->expectException(SectionNotFoundException::class);

        $this->sectionProvider->getById($sectionId);
    }
}
