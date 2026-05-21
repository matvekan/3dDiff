<?php

namespace App\Application\QueryHandler\User;


use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Dto\UserDto;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Query\User\GetUserByIdQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserDtoFactory $userDtoFactory,
    )
    {}

    public function __invoke(GetUserByIdQuery $query): ?UserDto
    {

        return $this->userDtoFactory->create($this->userRepository->getById(($query->id)));


    }


}
