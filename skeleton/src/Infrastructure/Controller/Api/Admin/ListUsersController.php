<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/admin/users', name: 'admin.users.list', methods: ['GET'])]
final class ListUsersController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private BearerTokenExtractor $tokenExtractor,
    ){}

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);
        $name = trim((string) $request->query->get('name', ''));
        $email = trim((string) $request->query->get('email', ''));
        $role = trim((string) $request->query->get('role', ''));
        $interest = trim((string) $request->query->get('interest', ''));
        return new JsonResponse($this->queryBus->execute(new ListUsersQuery($token, $name, $email, $role, $interest)));
    }
}
