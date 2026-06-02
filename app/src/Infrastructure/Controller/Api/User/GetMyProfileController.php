<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\User\GetMyProfileQuery;
use App\Domain\Entity\User;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/me', name: 'user.me.profile', methods: ['GET'])]
#[OA\Tag(name: 'Users')]
#[OA\Response(response: 200, description: 'Current user profile')]
class GetMyProfileController
{
    public function __construct(private QueryBusInterface $queryBus){}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        return new JsonResponse($this->queryBus->execute(new GetMyProfileQuery((string) $user->getId())));
    }
}
