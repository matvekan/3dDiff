<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Interest;
use App\Domain\Repository\InterestRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @extends ServiceEntityRepository<Interest>
 */
class InterestRepository extends ServiceEntityRepository implements InterestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interest::class);
    }

    public function save(Interest $interest): void
    {
        $this->getEntityManager()->persist($interest);
        $this->getEntityManager()->flush();
    }

    public function remove(Interest $interest): void
    {
        $this->getEntityManager()->remove($interest);
        $this->getEntityManager()->flush();
    }

    public function getById(string $id): Interest
    {
        $interest = $this->find($id);
        if (!$interest instanceof Interest) {
            throw new RuntimeException('Interest not found');
        }

        return $interest;
    }

    /**
     * @param array<string> $ids
     * @return array<Interest>
     */
    public function findByIds(array $ids): array
    {
        if ([] === $ids) {
            return [];
        }

        return $this->findBy(['id' => $ids]);
    }

    /**
     * @return array<Interest>
     */
    public function findAllOrderedByName(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }
}
