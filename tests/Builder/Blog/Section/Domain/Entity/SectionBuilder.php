<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Section\Domain\Entity;

use App\Blog\Section\Domain\Entity\Section;
use App\Blog\Section\Domain\ValueObject\SectionId;
use App\Blog\Section\Domain\ValueObject\SectionName;
use Faker\Factory;

final class SectionBuilder
{
    private SectionId $id;
    private SectionName $name;

    public function __construct()
    {
        $factory = Factory::create();

        $this->id = new SectionId($factory->uuid());
        $this->name = new SectionName($factory->name());
    }

    public function build(): Section
    {
        return new Section(
            $this->id,
            $this->name
        );
    }
}
