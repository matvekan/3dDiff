<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\User;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\User\UpdateMyInterestsCommand;
use App\Domain\Repository\InterestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class UpdateMyInterestsCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interests,
        private UserRepositoryInterface $users,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(UpdateMyInterestsCommand $command): void
    {
        $user = $this->users->getById($command->userId);

        $user->syncInterests($this->interests->findByIds($command->interestIds));

        $this->users->save($user);
        $this->cache->invalidateTags(['users_list']);
    }
}
