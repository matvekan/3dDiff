<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\CreateInterestCommand;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/interests', name: 'interest.create', methods: ['POST'])]
final readonly class CreateInterestController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[CurrentUser] User $user, #[MapRequestPayload] CreateInterestCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new CreateInterestCommand($command->name, (string) $user->getId())), 201);
    }
}
