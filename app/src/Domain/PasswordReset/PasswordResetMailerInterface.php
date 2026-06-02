<?php

namespace App\Domain\PasswordReset;

interface PasswordResetMailerInterface
{
    public function sendResetLink(string $toEmail, string $token): void;
}
