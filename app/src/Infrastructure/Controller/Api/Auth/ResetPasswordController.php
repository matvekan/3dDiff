<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\ResetPasswordCommand;
use App\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/reset-password', name: 'auth.reset_password', methods: ['POST'])]
class ResetPasswordController
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(#[MapRequestPayload] ResetPasswordCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
