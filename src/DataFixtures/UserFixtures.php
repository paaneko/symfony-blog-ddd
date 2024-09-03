<?php

namespace App\DataFixtures;

use App\Tests\Builder\Auth\User\Entity\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public const string USER_REFERENCE = 'user_';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 20; ++$i) {
            $user = (new UserBuilder())->active()->build();
            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }
}