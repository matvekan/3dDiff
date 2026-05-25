<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\User\GetMyProfileQuery;
use App\Application\Service\BearerTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/me', name: 'user.me.profile', methods: ['GET'])]
final class GetMyProfileController
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private BearerTokenExtractor $tokenExtractor,
    ){}

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->tokenExtractor->fromRequest($request);

        return new JsonResponse($this->queryBus->execute(new GetMyProfileQuery($token)));
    }
}
