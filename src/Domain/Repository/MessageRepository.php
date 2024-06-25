<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Entity\Message;
use App\Domain\Model\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /** @return list<Message> */
    public function getMessagesForUser(User $user, int $offset): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.offset <= :offset')
            ->andWhere('m.offset > :lastOffset')
            ->andWhere('m.userId = :id OR m.userId IS NULL')
            ->setParameter('offset', $offset)
            ->setParameter('lastOffset', $user->getLastReadOffset())
            ->setParameter('id', $user->getId(), UuidType::NAME)
            ->getQuery()
            ->getResult();
    }
}
