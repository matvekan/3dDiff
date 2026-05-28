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
        private InterestRepositoryInterface $interestRepository,
        private UserRepositoryInterface $userRepository,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(UpdateMyInterestsCommand $command): void
    {
        $user = $this->userRepository->getById($command->userId);

        foreach ($user->getInterest()->toArray() as $interest) {
            $user->removeInterest($interest);
        }

        foreach ($this->interestRepository->findByIds($command->interestIds) as $interest) {
            $user->addInterest($interest);
        }

        $this->userRepository->save($user);
        $this->cache->invalidateTags(['users_list']);
    }
}
