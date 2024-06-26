<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\AddUserMessageCommand;
use App\Domain\Model\Entity\Message;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class AddUserMessageCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(AddUserMessageCommand $command): void
    {
        $user = $this->userRepository->findUserById($command->userId);

        if (!$user instanceof \App\Domain\Model\Entity\User) {
            return;
        }

        $message = new Message(
            Uuid::v7(),
            $command->content,
            $user->getLastReadOffset() + 2,
            $user->getUsername(),
            $user->getId()
        );

        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}
