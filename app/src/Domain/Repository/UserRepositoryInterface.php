<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getByEmail(string $email): ?User;

    public function getByName(string $name): ?User;

    public function getById(string $id): ?User;

    public function remove(User $user): void;

    /**
     * @return array<User>
     */
    public function findByFilters(string $name, string $email, string $role, string $interest): array;
}
