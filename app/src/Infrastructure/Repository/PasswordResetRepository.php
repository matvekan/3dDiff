<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\PasswordReset;
use App\Domain\Repository\PasswordResetRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PasswordReset>
 */
class PasswordResetRepository extends ServiceEntityRepository implements PasswordResetRepositoryInterface
{
     public function __construct(ManagerRegistry $registry)
     {
         parent::__construct($registry, PasswordReset::class);
     }

    public function save(PasswordReset $passwordReset): PasswordReset
    {
        $this->getEntityManager()->persist($passwordReset);
        $this->getEntityManager()->flush();
        return $passwordReset;
    }

    public function findValidByToken(string $token): ?PasswordReset
    {
        $result = $this->createQueryBuilder('pr')
            ->andWhere('pr.token = :token')
            ->andWhere('pr.usedAt IS NULL')
            ->andWhere('pr.expiresAt > :now')
            ->setParameter('token', $token)
            ->setParameter('now', new DateTimeImmutable())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result instanceof PasswordReset ? $result : null;    }
}
