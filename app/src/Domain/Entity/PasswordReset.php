<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Token;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PasswordReset extends AbstractEntity
{
    #[ORM\Column(type: 'token', length: 255)]
    private Token $token;

    #[ORM\Column(type: 'email')]
    private Email $email;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $usedAt = null;

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function updateToken(Token $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function updateEmail(Email $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function updateExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updateCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function markAsUsed(?\DateTimeImmutable $usedAt): static
    {
        $this->usedAt = $usedAt;

        return $this;
    }
}
