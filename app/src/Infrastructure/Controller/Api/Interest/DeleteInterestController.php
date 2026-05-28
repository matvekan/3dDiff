<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests/{id}', name: 'interest.delete', methods: ['DELETE'])]
class DeleteInterestController
{
    public function __construct(private CommandBusInterface $commandBus) {}

    public function __invoke(string $id): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new DeleteInterestCommand($id)));
    }
}
