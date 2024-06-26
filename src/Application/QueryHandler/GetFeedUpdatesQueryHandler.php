<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetFeedUpdatesQuery;
use App\Domain\Model\Entity\Message;
use App\Domain\Model\Entity\React;
use App\Domain\Model\Entity\User;
use App\Domain\Repository\MessageRepository;
use App\Domain\Repository\ReactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class GetFeedUpdatesQueryHandler
{
    public function __construct(
        private readonly Security $security,
        private readonly MessageRepository $messageRepository,
        private readonly ReactRepository $reactRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /** @return list<React|Message> */
    public function handle(GetFeedUpdatesQuery $feedUpdatesQuery): array
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return [];
        }

        $offset = (new \DateTimeImmutable())->getTimestamp() - $currentUser->getCreatedAt()->getTimestamp();

        $messages = $this->messageRepository->getMessagesForUser($currentUser, $offset);
        $reacts = $this->reactRepository->getReactsForUser($currentUser, $offset);

        $feed = array_merge($messages, $reacts);

        usort($feed, static fn (Message|React $a, Message|React $b) => $a->getOffset() <=> $b->getOffset());

        $currentUser->setLastReadOffset($offset);
        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        return $feed;
    }
}
