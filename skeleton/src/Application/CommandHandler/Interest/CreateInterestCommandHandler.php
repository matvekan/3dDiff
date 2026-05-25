<?php
declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\CreateInterestCommand;
use App\Domain\Entity\Interest;
use App\Domain\Repository\InterestRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class CreateInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private UserRepositoryInterface $userRepository,
        private TagAwareCacheInterface $cache,
    ) {}

    public function __invoke(CreateInterestCommand $command): array
    {
        $admin = $this->userRepository->getById($command->adminId);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        $name = trim($command->name);
        if ('' === $name) {
            throw new BadRequestHttpException('name is required');
        }

        $interest = new Interest();
        $interest->setName($name);
        $this->interestRepository->save($interest);
        $this->cache->invalidateTags(['users_list']);

        return ['id' => (string) $interest->getId(), 'name' => $interest->getName()];
    }
}
