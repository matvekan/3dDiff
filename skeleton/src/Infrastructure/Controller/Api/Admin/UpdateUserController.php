<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Command\Admin\UpdateUserCommand;
use App\Application\Command\CommandBusInterface;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users/{id}', name: 'admin.users.update', methods: ['PUT'])]
final class UpdateUserController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private BearerTokenExtractor $tokenExtractor,
    ){}

    public function __invoke(string $id, Request $request, #[MapRequestPayload] UpdateUserCommand $command): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);

        return new JsonResponse($this->commandBus->execute(new UpdateUserCommand(
            $command->name,
            $command->role,
            $command->interestIds,
            $token,
            $id,
        )));
    }
}
