<?php

namespace App\Application\Dto\Factory;

use App\Application\Dto\UserDto;
use App\Domain\Entity\User;

class UserDtoFactory
{
    public function create(User $user): UserDto
    {
        return new UserDto(
            (string)$user->getId(),
            $user->getEmail(),
            $user->getName(),
            $user->getRole(),
            $user->getCreatedAt(),
            $user->getInterestIds()
        );
    }

    public function createFromArray(array $users): array
    {
        return array_map(fn(User $user) => $this->create($user), $users);
    }

}
