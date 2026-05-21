<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\User\GetUserByIdQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/v1/users', name: 'api_users_')]
final class UserController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    #[Route('/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getById(string $id): JsonResponse
    {
        if (!Uuid::isValid($id)) {
            return new JsonResponse(['error' => 'Invalid ID format'], Response::HTTP_BAD_REQUEST);
        }

        $query = new GetUserByIdQuery($id);

        /** @var \App\Application\Dto\UserDto|null $userDto */
        $userDto = $this->queryBus->execute($query);

        if (!$userDto) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($userDto, Response::HTTP_OK);
    }
}
