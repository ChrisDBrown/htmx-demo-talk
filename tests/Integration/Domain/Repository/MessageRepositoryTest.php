<?php

declare(strict_types=1);

namespace App\Tests\Integration\Domain\Repository;

use App\Domain\Model\Entity\Message;
use App\Domain\Model\Entity\User;
use App\Domain\Repository\MessageRepository;
use App\Tests\Integration\IntegrationTest;
use Symfony\Component\Uid\Uuid;

class MessageRepositoryTest extends IntegrationTest
{
    private MessageRepository $repository;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = static::getContainer()->get(MessageRepository::class);
    }

    public function testCanGetMessagesForOffset(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicMessage = new Message(
            Uuid::v7(),
            'Not by me',
            15,
            'harry',
            null
        );

        $privateMessage = new Message(
            Uuid::v7(),
            'By me',
            20,
            'billy',
            $user->getId()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicMessage);
        $this->entityManager->persist($privateMessage);
        $this->entityManager->flush();

        $expected = [$publicMessage, $privateMessage];

        $actual = $this->repository->getMessagesForUser($user, 30);

        self::assertEquals($expected, $actual);
    }

    public function testCanGetMessagesFilteredByOffset(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicMessage = new Message(
            Uuid::v7(),
            'Not by me',
            15,
            'harry',
            null
        );

        $privateMessage = new Message(
            Uuid::v7(),
            'By me',
            20,
            'billy',
            $user->getId()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicMessage);
        $this->entityManager->persist($privateMessage);
        $this->entityManager->flush();

        $user->setLastReadOffset(18);

        $expected = [$privateMessage];

        $actual = $this->repository->getMessagesForUser($user, 30);

        self::assertEquals($expected, $actual);
    }

    public function testCanGetMessagesFilteredByUser(): void
    {
        $user = new User(
            Uuid::v7(),
            'billy'
        );

        $publicMessage = new Message(
            Uuid::v7(),
            'Not by me',
            15,
            'harry',
            null
        );

        $privateMessage = new Message(
            Uuid::v7(),
            'By me',
            20,
            'billy',
            Uuid::v7()
        );

        $this->entityManager->persist($user);
        $this->entityManager->persist($publicMessage);
        $this->entityManager->persist($privateMessage);
        $this->entityManager->flush();

        $expected = [$publicMessage];

        $actual = $this->repository->getMessagesForUser($user, 30);

        self::assertEquals($expected, $actual);
    }
}
