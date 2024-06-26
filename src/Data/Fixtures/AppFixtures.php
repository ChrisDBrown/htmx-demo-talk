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
        $fileContents = file_get_contents(__DIR__.'/fake_tweets_large.csv');
        if (false === $fileContents) {
            throw new \RuntimeException('Cannot open tweet file for fixtures');
        }

        $rows = explode(\PHP_EOL, $fileContents);
        array_shift($rows);

        $time = 0;
        while ($time < 60 * 60) {
            $rowParts = explode(',', array_shift($rows) ?? '');

            $message = new Message(
                Uuid::v7(),
                $rowParts[1] ?? $faker->realText(),
                $time,
                preg_replace('/[^a-z0-9]/i', '', $faker->userName()) ?? 'chrisdbrown',
                null
            );

            $manager->persist($message);

            $time += random_int(5, 30);
        }

        $time = 0;
        while ($time < 60 * 60) {
            /** @var ReactType $react */
            $react = $faker->randomElement(ReactType::class);

            $message = new React(
                Uuid::v7(),
                $react,
                $time,
                preg_replace('/[^a-z0-9]/i', '', $faker->userName()) ?? 'chrisdbrown',
                null
            );

            $manager->persist($message);

            $time += random_int(2, 20);
        }

        $manager->flush();
    }
}
