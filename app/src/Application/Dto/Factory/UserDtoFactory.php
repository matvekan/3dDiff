<?php

namespace App\Application\Dto\Factory;

use App\Application\Dto\UserDto;
use App\Domain\Entity\User;

class UserDtoFactory
{
    public function create(User $user): UserDto
    {
        $interests = [];
        foreach ($user->getInterest() as $interest) {
            $interests[] = [
                'id' => (string) $interest->getId(),
                'name' => $interest->getName(),
            ];
        }

        return new UserDto(
            (string)$user->getId(),
            $user->getEmail()->toValue(),
            $user->getName()->toValue(),
            $user->getRole(),
            $user->getCreatedAt(),
            $interests
        );
    }

    /**
     * @param array<User> $users
     * @return array<UserDto>
     */
    public function createFromArray(array $users): array
    {
        return array_map(fn(User $user) => $this->create($user), $users);
    }

}
