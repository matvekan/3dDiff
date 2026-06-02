<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\ResetPasswordCommand;
use App\Application\Command\CommandBusInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/reset-password', name: 'auth.reset_password', methods: ['POST'])]
#[OA\Tag(name: 'Auth')]
#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: ResetPasswordCommand::class)))]
#[OA\Response(response: 204, description: 'Password reset successfully')]
#[OA\Response(response: 400, description: 'Invalid or expired token')]
#[OA\Response(response: 422, description: 'Validation error')]
class ResetPasswordController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[MapRequestPayload] ResetPasswordCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
