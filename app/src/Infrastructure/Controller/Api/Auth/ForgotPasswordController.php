<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Auth;

use App\Application\Command\Auth\ForgotPasswordCommand;
use App\Application\Command\CommandBusInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/auth/forgot-password', name: 'auth.forgot_password', methods: ['POST'])]
#[OA\Tag(name: 'Auth')]
#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: ForgotPasswordCommand::class)))]
#[OA\Response(response: 204, description: 'Password reset link sent (always returns 204 to avoid email enumeration)')]
#[OA\Response(response: 422, description: 'Validation error')]
class ForgotPasswordController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[MapRequestPayload] ForgotPasswordCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute($command));
    }
}
