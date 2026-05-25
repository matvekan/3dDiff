<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\User;

final readonly class AuthTokenService
{
    public function __construct(
        private string $appSecret,
        private int $ttlSeconds = 86400,
    ) {
    }

    public function create(User $user): string
    {
        $payload = [
            'uid' => (string) $user->getId(),
            'exp' => time() + $this->ttlSeconds,
        ];

        $json = json_encode($payload, JSON_THROW_ON_ERROR);
        $encoded = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $encoded, $this->appSecret);

        return $encoded.'.'.$signature;
    }

    public function parse(string $token): ?array
    {
        $parts = explode('.', $token, 2);
        if (2 !== count($parts)) {
            return null;
        }

        [$encoded, $signature] = $parts;
        $expected = hash_hmac('sha256', $encoded, $this->appSecret);

        if (!hash_equals($expected, $signature)) {
            return null;
        }

        $decoded = base64_decode(strtr($encoded, '-_', '+/'), true);
        if (false === $decoded) {
            return null;
        }

        $payload = json_decode($decoded, true);
        if (!is_array($payload) || !isset($payload['uid'], $payload['exp'])) {
            return null;
        }

        if ((int) $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }
}
