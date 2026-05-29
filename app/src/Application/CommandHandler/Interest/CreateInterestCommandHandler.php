<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\CreateInterestCommand;
use App\Domain\Entity\Interest;
use App\Domain\Repository\InterestRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class CreateInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(CreateInterestCommand $command): void
    {
        $interest = new Interest();
        $interest->setName($command->name);
        $this->interestRepository->save($interest);
        $this->cache->invalidateTags(['users_list']);
    }
}
