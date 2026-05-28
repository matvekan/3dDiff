<?php
declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\UpdateInterestCommand;
use App\Domain\Repository\InterestRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class UpdateInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private TagAwareCacheInterface $cache,
    ) {}

    public function __invoke(UpdateInterestCommand $command): void
    {
        try {
            $interest = $this->interestRepository->getById($command->interestId);
        } catch (\RuntimeException) {
            throw new NotFoundHttpException('Interest not found');
        }

        $interest->setName($command->name);
        $this->interestRepository->save($interest);
        $this->cache->invalidateTags(['users_list']);
    }
}
