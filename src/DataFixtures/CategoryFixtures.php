<?php

namespace App\DataFixtures;

use App\Tests\Builder\Blog\Category\Domain\Entity\CategoryBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixtures extends Fixture
{
    public const string CATEGORY_REFERENCE = 'category_';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 5; ++$i) {
            $category = (new CategoryBuilder())->build();
            $manager->persist($category);
            $this->addReference(self::CATEGORY_REFERENCE . $i, $category);
        }

        $manager->flush();
    }
}