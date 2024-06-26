<?php

declare(strict_types=1);

namespace App\Tests\Integration\Domain\Repository;

use App\Domain\Model\Entity\React;
use App\Domain\Model\Entity\User;
use App\Domain\Model\Enum\ReactType;
use App\Domain\Repository\ReactRepository;
use App\Tests\Integration\IntegrationTest;
use Symfony\Component\Uid\Uuid;

class ReactRepositoryTest extends IntegrationTest
{
    private ReactRepository $repository;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::getContainer()->get(ReactRepository::class);
    }

    public function testCanGetReactsForOffset(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicReact = new React(
            Uuid::v7(),
            ReactType::LOVE,
            15,
            'harry',
            null
        );

        $privateReact = new React(
            Uuid::v7(),
            ReactType::SMILE,
            20,
            'billy',
            $user->getId()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicReact);
        $this->entityManager->persist($privateReact);
        $this->entityManager->flush();

        $expected = [$publicReact, $privateReact];

        $actual = $this->repository->getReactsForUser($user->getId(), 0, 30);

        self::assertEquals($expected, $actual);
    }

    public function testCanGetReactsFilteredByOffset(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicReact = new React(
            Uuid::v7(),
            ReactType::LOVE,
            15,
            'harry',
            null
        );

        $privateReact = new React(
            Uuid::v7(),
            ReactType::SMILE,
            20,
            'billy',
            $user->getId()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicReact);
        $this->entityManager->persist($privateReact);
        $this->entityManager->flush();

        $expected = [$privateReact];

        $actual = $this->repository->getReactsForUser($user->getId(), 18, 30);

        self::assertEquals($expected, $actual);
    }

    public function testCanGetReactsFilteredByUser(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicReact = new React(
            Uuid::v7(),
            ReactType::LOVE,
            15,
            'harry',
            null
        );

        $privateReact = new React(
            Uuid::v7(),
            ReactType::SMILE,
            20,
            'billy',
            Uuid::v7()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicReact);
        $this->entityManager->persist($privateReact);
        $this->entityManager->flush();

        $expected = [$publicReact];

        $actual = $this->repository->getReactsForUser($user->getId(), 0, 30);

        self::assertEquals($expected, $actual);
    }
}
