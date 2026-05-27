<?php

namespace App\Application\QueryHandler\User;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Query\User\GetUsersQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserDtoFactory          $userDtoFactory,
    ){}


    public function __invoke(GetUsersQuery $query): array
    {
        return $this->userDtoFactory->createFromArray($this->userRepository->findAll());
    }

}
