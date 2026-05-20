<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Interest;
use App\Domain\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    public function findById(Uuid $id): ?User;

    public function remove(User $user): void;

    /**
     * @param Interest $interest
     * @return array<User>
     */
    public function getByInterest(Interest $interest): array;


}
