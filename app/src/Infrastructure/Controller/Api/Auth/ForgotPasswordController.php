<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\ForgotPasswordCommand;
use App\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/forgot-password', name: 'auth.forgot_password', methods: ['POST'])]
class ForgotPasswordController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[MapRequestPayload] ForgotPasswordCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
