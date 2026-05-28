<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Command\Admin\UpdateUserCommand;
use App\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users/{id}', name: 'admin.users.update', methods: ['PUT'])]
readonly class UpdateUserController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(string $id, #[MapRequestPayload] UpdateUserCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new UpdateUserCommand(
            $command->name,
            $command->role,
            $command->interestIds,
            $id,
        )));
    }
}
