<?php
declare(strict_types=1);

namespace App\Application\CommandHandler\Admin;

use App\Application\Command\Admin\UpdateUserCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Dto\Factory\UserDtoFactory;
use App\Domain\Repository\InterestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Name;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserDtoFactory $userDtoFactory,
        private UserRepositoryInterface $userRepository,
        private InterestRepositoryInterface $interestRepository,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(UpdateUserCommand $command): array
    {
        $admin = $this->userRepository->getById($command->adminId);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        try {
            $user = $this->userRepository->getById($command->userId);
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('User not found');
        }

        if (null !== $command->name) {
            $user->setName(new Name($command->name));
        }
        if (null !== $command->role) {
            $user->setRole($command->role);
        }
        if (null !== $command->interestIds) {
            foreach ($user->getInterest()->toArray() as $interest) {
                $user->removeInterest($interest);
            }
            foreach ($this->interestRepository->findByIds($command->interestIds) as $interest) {
                $user->addInterest($interest);
            }
        }

        $this->userRepository->save($user);
        $this->cache->invalidateTags(['users_list']);

        return $this->userDtoFactory->create($user)->jsonSerialize();
    }
}
