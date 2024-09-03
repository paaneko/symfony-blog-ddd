<?php

namespace App\DataFixtures;

use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Tests\Builder\Blog\Article\Domain\Entity\ArticleBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $article = (new ArticleBuilder())
            ->withId(new ArticleId('c3d5d67a-986e-4733-a433-3924af775a4b'))
            ->withAuthor(
                new ArticleAuthorId(
                    $this->getReference('user_'. $faker->numberBetween(0,20))->getId()->getValue()
                )
            )
            ->withCategory(
                new CategoryId(
                    $this->getReference('category_'. $faker->numberBetween(0,5))->getId()
                )
            )
            ->build();
        $manager->persist($article);

        for ($i = 0; $i <= 40; ++$i) {
            $article = (new ArticleBuilder())
                ->withAuthor(
                    new ArticleAuthorId(
                        $this->getReference(UserFixtures::USER_REFERENCE. $faker->numberBetween(0,20))->getId()->getValue()
                    )
                )
                ->withCategory(
                    new CategoryId(
                        $this->getReference(CategoryFixtures::CATEGORY_REFERENCE. $faker->numberBetween(0,5))->getId()
                    )
                )
                ->build();
            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}