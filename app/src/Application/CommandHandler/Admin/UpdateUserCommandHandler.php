<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Admin;

use App\Application\Command\Admin\UpdateUserCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Domain\Repository\InterestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Name;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
        private InterestRepositoryInterface $interests,
        private TagAwareCacheInterface $cache,
    )
    {}

    public function __invoke(UpdateUserCommand $command): void
    {
        try {
            $user = $this->users->getById($command->userId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('User not found');
        }

        if (null !== $command->name) {
            $user->updateName(new Name($command->name));
        }
        if (null !== $command->role) {
            $user->updateRole($command->role);
        }
        if (null !== $command->interestIds) {
            $user->syncInterests($this->interests->findByIds($command->interestIds));
        }

        $this->users->save($user);
        $this->cache->invalidateTags(['users_list']);
    }
}
