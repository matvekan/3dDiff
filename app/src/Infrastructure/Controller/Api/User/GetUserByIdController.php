<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\User\GetUserByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/users/{id}', name: 'api_users_get_by_id', methods: ['GET'])]
class GetUserByIdController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $userDto = $this->queryBus->execute(new GetUserByIdQuery($id));
        return new JsonResponse($userDto, Response::HTTP_OK);
    }
}
