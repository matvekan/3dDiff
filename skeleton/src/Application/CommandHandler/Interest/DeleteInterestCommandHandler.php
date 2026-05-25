<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Interest;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use App\Application\Service\AuthenticatedUserResolver;
use App\Domain\Repository\InterestRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteInterestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private InterestRepositoryInterface $interestRepository,
        private AuthenticatedUserResolver $userResolver,
    ) {
    }

    public function __invoke(DeleteInterestCommand $command): array
    {
        $admin = $this->userResolver->fromToken($command->token);
        if ('ROLE_ADMIN' !== $admin->getRole()) {
            throw new AccessDeniedHttpException('Forbidden');
        }

        try {
            $interest = $this->interestRepository->getById($command->interestId);
        } catch (\RuntimeException) {
            throw new NotFoundHttpException('Interest not found');
        }

        $this->interestRepository->remove($interest);

        return ['message' => 'Interest deleted'];
    }
}
