<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Command\Admin\UpdateUserCommand;
use App\Application\Command\CommandBusInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users/{id}', name: 'admin.users.update', methods: ['PUT'])]
#[OA\Tag(name: 'Admin')]
#[OA\Parameter(name: 'id', description: 'User UUID', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))]
#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: UpdateUserCommand::class)))]
#[OA\Response(response: 200, description: 'User updated')]
#[OA\Response(response: 404, description: 'User not found')]
#[OA\Response(response: 422, description: 'Validation error')]
class UpdateUserController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(string $id, #[MapRequestPayload] UpdateUserCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new UpdateUserCommand(
            $command->name,
            $command->role,
            $command->interestIds,
            $id,
        )));
    }
}
