<?php

namespace App\Application\QueryHandler\User;


use App\Application\Dto\UserDto;
use App\Application\Query\QuerryHandlerInterface;
use App\Application\Query\User\GetUserByIdQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUserByIdQueryHandler implements QuerryHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {

    }

    public function __invoke(GetUserByIdQuery $query): ?UserDto
    {
        return null;
        //TODO

    }


}
