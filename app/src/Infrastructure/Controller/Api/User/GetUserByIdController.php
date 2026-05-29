<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\User\GetUserByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/v1/users/{id}', name: 'api_users_get_by_id', methods: ['GET'])]
class GetUserByIdController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        if (!Uuid::isValid($id)) {
            return new JsonResponse(['error' => 'Invalid ID format'], Response::HTTP_BAD_REQUEST);
        }

        $userDto = $this->queryBus->execute(new GetUserByIdQuery($id));
        if (!$userDto) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($userDto, Response::HTTP_OK);
    }
}
