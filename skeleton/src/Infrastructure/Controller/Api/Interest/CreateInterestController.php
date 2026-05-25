<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\CreateInterestCommand;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests', name: 'interest.create', methods: ['POST'])]
final class CreateInterestController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private BearerTokenExtractor $tokenExtractor,
    ){}

    public function __invoke(Request $request, #[MapRequestPayload] CreateInterestCommand $command): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);

        return new JsonResponse($this->commandBus->execute(new CreateInterestCommand($command->name, $token)), 201);
    }
}
