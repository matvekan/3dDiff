<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users', name: 'admin.users.list', methods: ['GET'])]
readonly class ListUsersController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(#[MapQueryString] ListUsersQuery $query): JsonResponse
    {
        return new JsonResponse($this->queryBus->execute($query));
    }
}
