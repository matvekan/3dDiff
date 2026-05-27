<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Interest;

use App\Application\Query\Interest\ListInterestsQuery;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Interest;
use App\Domain\Repository\InterestRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ListInterestsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private InterestRepositoryInterface $interestRepository)
    {
    }

    public function __invoke(ListInterestsQuery $query): array
    {
        return array_map(static fn (Interest $interest) => [
            'id' => (string) $interest->getId(),
            'name' => $interest->getName(),
        ], $this->interestRepository->findAllOrderedByName());
    }
}
