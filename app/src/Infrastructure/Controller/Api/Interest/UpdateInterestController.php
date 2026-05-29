<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\UpdateInterestCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests/{id}', name: 'interest.update', methods: ['PUT'])]
class UpdateInterestController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(string $id, #[MapRequestPayload] UpdateInterestCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new UpdateInterestCommand($command->name, $id)));
    }
}
