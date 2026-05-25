<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Admin;

use App\Application\Query\Admin\ListUsersQuery;
use App\Application\Query\QueryBusInterface;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/admin/users', name: 'admin.users.list', methods: ['GET'])]
final class ListUsersController
{
    public function __construct(private QueryBusInterface $queryBus){}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $name = trim((string) $request->query->get('name', ''));
        $email = trim((string) $request->query->get('email', ''));
        $role = trim((string) $request->query->get('role', ''));
        $interest = trim((string) $request->query->get('interest', ''));

        return new JsonResponse($this->queryBus->execute(new ListUsersQuery(
            (string) $user->getId(),
            $name,
            $email,
            $role,
            $interest
        )));
    }
}
