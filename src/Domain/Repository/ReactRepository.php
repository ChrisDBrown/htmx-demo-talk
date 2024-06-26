<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Entity\React;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

/**
 * @extends ServiceEntityRepository<React>
 */
class ReactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, React::class);
    }

    /** @return list<React> */
    public function getReactsForUser(UuidV7 $userId, int $startOffset, int $endOffset): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.offset >= :startOffset')
            ->andWhere('r.offset < :endOffset')
            ->andWhere('r.userId = :id OR r.userId IS NULL')
            ->setParameter('startOffset', $startOffset)
            ->setParameter('endOffset', $endOffset)
            ->setParameter('id', $userId, UuidType::NAME)
            ->getQuery()
            ->getResult();
    }
}
