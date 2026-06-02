<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Interest\UpdateInterestCommand;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests/{id}', name: 'interest.update', methods: ['PUT'])]
#[OA\Tag(name: 'Interests')]
#[OA\Parameter(name: 'id', description: 'Interest UUID', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))]
#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: UpdateInterestCommand::class)))]
#[OA\Response(response: 200, description: 'Interest updated')]
#[OA\Response(response: 404, description: 'Interest not found')]
#[OA\Response(response: 422, description: 'Validation error')]
class UpdateInterestController
{
    public function __construct(private CommandBusInterface $commandBus) {}

    public function __invoke(string $id, #[MapRequestPayload] UpdateInterestCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new UpdateInterestCommand($command->name, $id)));
    }
}
