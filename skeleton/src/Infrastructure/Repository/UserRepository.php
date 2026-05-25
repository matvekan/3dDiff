<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Interest;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @extends ServiceEntityRepository<User>
 */

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface, UserProviderInterface
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

    public function getByEmail(string $email): User
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', new Email($email))
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function getByName(string $name): User
    {
        $user = $this->findOneBy(['name' => new Name($name)]);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function getById(string $id): User
    {
        $user = $this->findOneBy(["id" => $id]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function getByInterest(Interest $interest): array
    {
        return array(1, 2);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getByEmail($identifier);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->getById((string) $user->getId());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);    }
}
