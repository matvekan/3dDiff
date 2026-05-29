<?php

declare(strict_types=1);

namespace App\Application\Dto;

use DateTimeImmutable;
use JsonSerializable;


class UserDto implements JsonSerializable
{

    /**
     * @param string $id
     * @param string $email
     * @param string $name
     * @param string $role
     * @param DateTimeImmutable|null $createdAt
     * @param array<int, array{id: string, name: string|null}> $interests
     *
     */
    public function __construct(
        private string $id,
        private string $email,
        private string $name,
        private string $role,
        private ?DateTimeImmutable $createdAt,
        private array $interests,
    )
    {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<int, array{id: string, name: string|null}>
     */
    public function getInterest(): array
    {
        return $this->interests;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'name' => $this->name,
            'interests' => $this->interests,
        ];
    }
}
