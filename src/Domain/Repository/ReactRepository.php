<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Entity\React;
use App\Domain\Model\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

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
    public function getReactsForUser(User $user, int $offset): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.offset <= :offset')
            ->andWhere('r.offset > :lastOffset')
            ->andWhere('r.userId = :id OR r.userId IS NULL')
            ->setParameter('offset', $offset)
            ->setParameter('lastOffset', $user->getLastReadOffset())
            ->setParameter('id', $user->getId(), UuidType::NAME)
            ->getQuery()
            ->getResult();
    }
}
