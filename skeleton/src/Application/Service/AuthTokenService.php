<?php

declare(strict_types=1);

namespace App\Application\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final readonly class AuthTokenService
{
    public function __construct(
        private string $appSecret,
        private int $ttlSeconds = 86400,
    ) {
    }

    public function createForUser(string $userId, string $role): string
    {
        $payload = [
            'uid' => $userId,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + $this->ttlSeconds,
        ];

        return JWT::encode($payload, $this->appSecret, 'HS256');
    }

    public function parse(string $token): ?array
    {
        if ('' === trim($token)) {
            return null;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->appSecret, 'HS256'));
        } catch (\Throwable) {
            return null;
        }

        $payload = (array) $decoded;
        if (!is_array($payload) || !isset($payload['uid'], $payload['exp'])) {
            return null;
        }

        return $payload;
    }
}
