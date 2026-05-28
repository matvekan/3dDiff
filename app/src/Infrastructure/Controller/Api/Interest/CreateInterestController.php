<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\CreateInterestCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests', name: 'interest.create', methods: ['POST'])]
class CreateInterestController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[MapRequestPayload] CreateInterestCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
