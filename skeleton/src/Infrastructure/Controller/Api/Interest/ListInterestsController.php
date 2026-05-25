<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\Interest;

use App\Application\Query\Interest\ListInterestsQuery;
use App\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/interests', name: 'interest.list', methods: ['GET'])]
final class ListInterestsController
{
    public function __construct(private QueryBusInterface $queryBus){}

    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->queryBus->execute(new ListInterestsQuery()));
    }
}
