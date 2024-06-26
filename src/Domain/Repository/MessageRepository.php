<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

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
    public function getMessagesForUser(UuidV7 $userId, int $startOffset, int $endOffset): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.offset >= :startOffset')
            ->andWhere('m.offset < :endOffset')
            ->andWhere('m.userId = :id OR m.userId IS NULL')
            ->setParameter('startOffset', $startOffset)
            ->setParameter('endOffset', $endOffset)
            ->setParameter('id', $userId, UuidType::NAME)
            ->getQuery()
            ->getResult();
    }
}
