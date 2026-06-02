<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Admin;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class ListUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
        private UserDtoFactory $userDtoFactory,
        private TagAwareCacheInterface $cache,
    ) {
    }

    /**
     * @return array<mixed>
     *
     * @throws InvalidArgumentException
     * @throws JsonException|\JsonException
     */
    public function __invoke(ListUsersQuery $query): array
    {
        $cacheKey = 'admin_users_'.md5(json_encode([$query->name, $query->email, $query->role, $query->interest], JSON_THROW_ON_ERROR));

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($query) {
            $item->expiresAfter(60);
            $item->tag('users_list');

            $users = $this->users->findByFilters(
                $query->name,
                $query->email,
                $query->role,
                $query->interest
            );

            return array_map(
                static fn ($dto) => $dto->jsonSerialize(),
                $this->userDtoFactory->createFromArray($users)
            );
        });
    }
}
