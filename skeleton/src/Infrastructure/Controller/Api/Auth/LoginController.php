<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\LoginCommand;
use App\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/login', name: 'auth.login', methods: ['POST'])]
final class LoginController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[MapRequestPayload] LoginCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
