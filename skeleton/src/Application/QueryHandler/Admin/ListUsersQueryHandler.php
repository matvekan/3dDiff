<?php
declare(strict_types=1);

namespace App\Application\QueryHandler\Admin;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsMessageHandler]
final readonly class ListUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EntityManagerInterface $entityManager,
        private UserDtoFactory $userDtoFactory,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(ListUsersQuery $query): array
    {
        $admin = $this->userRepository->getById($query->adminId);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        $cacheKey = 'admin_users_'.md5(json_encode([$query->name, $query->email, $query->role, $query->interest], JSON_THROW_ON_ERROR));

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($query) {
            $item->expiresAfter(60);
            if ($item instanceof CacheItem) {
                $item->tag('users_list');
            }

            $qb = $this->entityManager->createQueryBuilder()
                ->select('u', 'i')
                ->from('App\\Domain\\Entity\\User', 'u')
                ->leftJoin('u.interest', 'i');

            if ('' !== $query->name) {
                $qb->andWhere('LOWER(u.name) LIKE :name')->setParameter('name', '%'.mb_strtolower($query->name).'%');
            }
            if ('' !== $query->email) {
                $qb->andWhere('LOWER(u.email) LIKE :email')->setParameter('email', '%'.mb_strtolower($query->email).'%');
            }
            if ('' !== $query->role) {
                $qb->andWhere('u.role = :role')->setParameter('role', $query->role);
            }
            if ('' !== $query->interest) {
                $qb->andWhere('LOWER(i.name) LIKE :interest')->setParameter('interest', '%'.mb_strtolower($query->interest).'%');
            }

            return array_map(
                static fn ($dto) => $dto->jsonSerialize(),
                $this->userDtoFactory->createFromArray($qb->getQuery()->getResult())
            );
        });
    }
}
