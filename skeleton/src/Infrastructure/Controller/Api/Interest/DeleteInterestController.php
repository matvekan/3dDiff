<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests/{id}', name: 'interest.delete', methods: ['DELETE'])]
final class DeleteInterestController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private BearerTokenExtractor $tokenExtractor,
    ) {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);

        return new JsonResponse($this->commandBus->execute(new DeleteInterestCommand($token, $id)));
    }
}
