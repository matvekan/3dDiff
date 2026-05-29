<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\User;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Query\User\GetMyProfileQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetMyProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserDtoFactory $userDtoFactory,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @param GetMyProfileQuery $query
     * @return array<string, mixed>
     */
    public function __invoke(GetMyProfileQuery $query): array
    {
        $user = $this->userRepository->getById($query->userId);

        return $this->userDtoFactory->create($user)->jsonSerialize();
    }
}
