<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\DeleteInterestCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests/{id}', name: 'interest.delete', methods: ['DELETE'])]
#[OA\Tag(name: 'Interests')]
#[OA\Parameter(name: 'id', description: 'Interest UUID', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))]
#[OA\Response(response: 200, description: 'Interest deleted')]
#[OA\Response(response: 404, description: 'Interest not found')]
class DeleteInterestController
{
    public function __construct(private CommandBusInterface $commandBus) {}

    public function __invoke(string $id): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new DeleteInterestCommand($id)));
    }
}
