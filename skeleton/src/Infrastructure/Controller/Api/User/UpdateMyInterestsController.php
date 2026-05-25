<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\User\UpdateMyInterestsCommand;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/me/interests', name: 'user.me.interests.update', methods: ['PUT'])]
final class UpdateMyInterestsController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private BearerTokenExtractor $tokenExtractor,
    ){}

    public function __invoke(Request $request, #[MapRequestPayload] UpdateMyInterestsCommand $command): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);

        return new JsonResponse($this->commandBus->execute(new UpdateMyInterestsCommand($command->interestIds, $token)));
    }
}
