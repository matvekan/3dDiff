<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\UpdateInterestCommand;
use App\Application\Service\AuthenticatedUserResolver;
use App\Domain\Repository\InterestRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class UpdateInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private AuthenticatedUserResolver $userResolver,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(UpdateInterestCommand $command): array
    {
        $admin = $this->userResolver->fromToken($command->token);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        $name = trim($command->name);
        if ('' === $name) {
            throw new BadRequestHttpException('name is required');
        }

        try {
            $interest = $this->interestRepository->getById($command->interestId);
        } catch (\RuntimeException) {
            throw new NotFoundHttpException('Interest not found');
        }

        $interest->setName($name);
        $this->interestRepository->save($interest);
        $this->cache->invalidateTags(['users_list']);

        return ['id' => (string) $interest->getId(), 'name' => $interest->getName(), 'message' => 'Interest updated'];
    }
}
