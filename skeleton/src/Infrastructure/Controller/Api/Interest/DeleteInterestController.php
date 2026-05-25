<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/interests/{id}', name: 'interest.delete', methods: ['DELETE'])]
final class DeleteInterestController
{
    public function __construct(private CommandBusInterface $commandBus) {}

    public function __invoke(string $id, #[CurrentUser] User $user): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new DeleteInterestCommand((string) $user->getId(), $id)));
    }
}
