<?php

declare(strict_types=1);

namespace App\Data\Fixtures;

use App\Domain\Model\Entity\Message;
use App\Domain\Model\Entity\React;
use App\Domain\Model\Enum\ReactType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $time = 0;
        while ($time < 60 * 60) {
            $message = new Message(
                Uuid::v7(),
                $faker->realText(),
                $time,
                preg_replace('/[^a-z0-9]/i', '', $faker->userName()),
                null
            );

            $manager->persist($message);

            $time += random_int(5, 40);
        }

        $time = 0;
        while ($time < 60 * 60) {
            $message = new React(
                Uuid::v7(),
                $faker->randomElement(ReactType::class),
                $time,
                preg_replace('/[^a-z0-9]/i', '', $faker->userName()),
                null
            );

            $manager->persist($message);

            $time += random_int(5, 40);
        }

        $manager->flush();
    }
}
