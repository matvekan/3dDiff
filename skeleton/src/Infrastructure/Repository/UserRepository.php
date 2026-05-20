<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Interest;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Uid\Uuid;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return null;
    }

    public function findByUsername(string $username): ?User
    {
        return null;
    }

    public function findById(Uuid $id): ?User
    {
        $user = $this->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function remove(User $user): void
    {
        $this->remove($user);
    }

    public function getByInterest(Interest $interest): array
    {
        return array(1, 2);
    }

}
