<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryBusInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users', name: 'admin.users.list', methods: ['GET'])]
#[OA\Tag(name: 'Admin')]
#[OA\Parameter(name: 'name', description: 'Filter by name', in: 'query', schema: new OA\Schema(type: 'string'))]
#[OA\Parameter(name: 'email', description: 'Filter by email', in: 'query', schema: new OA\Schema(type: 'string'))]
#[OA\Parameter(name: 'role', description: 'Filter by role', in: 'query', schema: new OA\Schema(type: 'string'))]
#[OA\Parameter(name: 'interest', description: 'Filter by interest name', in: 'query', schema: new OA\Schema(type: 'string'))]
#[OA\Response(response: 200, description: 'List of users')]
class ListUsersController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(#[MapQueryString] ListUsersQuery $query): JsonResponse
    {
        return new JsonResponse($this->queryBus->execute($query));
    }
}
