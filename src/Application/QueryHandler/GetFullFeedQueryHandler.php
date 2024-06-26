<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetFullFeedQuery;
use App\Domain\Model\Entity\Message;
use App\Domain\Model\Entity\React;
use App\Domain\Model\Entity\User;
use App\Domain\Repository\MessageRepository;
use App\Domain\Repository\ReactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class GetFullFeedQueryHandler
{
    public function __construct(
        private readonly Security $security,
        private readonly MessageRepository $messageRepository,
        private readonly ReactRepository $reactRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /** @return list<React|Message> */
    public function handle(GetFullFeedQuery $query): array
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return [];
        }

        $offset = (new \DateTimeImmutable())->getTimestamp() - $currentUser->getCreatedAt()->getTimestamp();

        $messages = $this->messageRepository->getMessagesForUser($currentUser->getId(), 0, $offset);
        $reacts = $this->reactRepository->getReactsForUser($currentUser->getId(), 0, $offset);

        $feed = array_merge($messages, $reacts);

        usort($feed, static fn (Message|React $a, Message|React $b) => $a->getOffset() <=> $b->getOffset());

        $currentUser->setLastReadOffset($offset);
        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        return $feed;
    }
}
