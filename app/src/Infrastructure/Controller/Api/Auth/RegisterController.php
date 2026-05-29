<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\RegisterUserCommand;
use App\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/register', name: 'auth.register', methods: ['POST'])]
class RegisterController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(#[MapRequestPayload] RegisterUserCommand $command): JsonResponse
    {
        $this->commandBus->execute($command);

        return new JsonResponse(null, 201);
    }
}
