<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\AddUserReactCommand;
use App\Domain\Model\Entity\React;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class AddUserReactCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(AddUserReactCommand $command): void
    {
        $user = $this->userRepository->findUserById($command->userId);

        if (!$user instanceof \App\Domain\Model\Entity\User) {
            return;
        }

        $react = new React(
            Uuid::v7(),
            $command->type,
            $user->getLastReadOffset(),
            $user->getUsername(),
            $user->getId()
        );

        $this->entityManager->persist($react);
        $this->entityManager->flush();
    }
}
