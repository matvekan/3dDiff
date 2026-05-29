<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements UserProviderInterface<User>
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
        /** @var User|null $user */
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

    public function getById(string $id): User
    {
        $user = $this->findOneBy(['id' => $id]);

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

    public function findByFilters(string $name, string $email, string $role, string $interest): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u', 'i')
            ->leftJoin('u.interest', 'i');

        if ('' !== $name) {
            $qb->andWhere('LOWER(u.name) LIKE :name')
                ->setParameter('name', '%'.mb_strtolower($name).'%');
        }
        if ('' !== $email) {
            $qb->andWhere('LOWER(u.email) LIKE :email')
                ->setParameter('email', '%'.mb_strtolower($email).'%');
        }
        if ('' !== $role) {
            $qb->andWhere('u.role = :role')
                ->setParameter('role', $role);
        }
        if ('' !== $interest) {
            $qb->andWhere('LOWER(i.name) LIKE :interest')
                ->setParameter('interest', '%'.mb_strtolower($interest).'%');
        }

        /** @var array<User> $result */
        $result = $qb->getQuery()->getResult();

        return $result;
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
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
