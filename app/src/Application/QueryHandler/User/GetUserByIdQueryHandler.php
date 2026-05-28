<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\User;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Dto\UserDto;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Query\User\GetUserByIdQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

#[AsMessageHandler]
final readonly class GetUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserDtoFactory $userDtoFactory,
    ) {}

    public function __invoke(GetUserByIdQuery $query): ?UserDto
    {
        try {
            $user = $this->userRepository->getById($query->id);
        } catch (UserNotFoundException) {
            return null;
        }

        return $this->userDtoFactory->create($user);
    }
}
