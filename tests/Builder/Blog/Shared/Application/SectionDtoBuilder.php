<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Shared\Application;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Shared\Application\Dto\SectionDto;
use Faker\Factory;

final class SectionDtoBuilder
{
    private string $id;
    private string $name;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = $faker->uuid();
        $this->name = $faker->name();
    }

    public function fromArticle(Article $article): self
    {
        $clone = clone $this;
        $clone->id = $article->getSectionId()->getValue();

        return $clone;
    }

    public function fromSection(Section $section): self
    {
        $clone = clone $this;
        $clone->id = $section->getId()->getValue();
        $clone->name = $section->getName()->getValue();

        return $clone;
    }

    public function build(): SectionDto
    {
        return new SectionDto(
            $this->id,
            $this->name
        );
    }
}
