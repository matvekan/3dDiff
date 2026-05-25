<?php
declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use App\Domain\Repository\InterestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class DeleteInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private UserRepositoryInterface $userRepository,
        private TagAwareCacheInterface $cache,
    ) {}

    public function __invoke(DeleteInterestCommand $command): array
    {
        $admin = $this->userRepository->getById($command->adminId);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        try {
            $interest = $this->interestRepository->getById($command->interestId);
        } catch (\RuntimeException) {
            throw new NotFoundHttpException('Interest not found');
        }

        $this->interestRepository->remove($interest);
        $this->cache->invalidateTags(['users_list']);

        return ['message' => 'Interest deleted'];
    }
}
