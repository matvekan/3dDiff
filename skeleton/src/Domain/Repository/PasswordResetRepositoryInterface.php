<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function save(PasswordReset $passwordReset): PasswordReset;

    public function findValidByToken(string $token): ?PasswordReset;
}
