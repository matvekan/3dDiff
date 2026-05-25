<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use AllowDynamicProperties;
use Symfony\Component\String\Exception\InvalidArgumentException;
use Yokai\DoctrineValueObject\StringValueObject;

final class Email implements StringValueObject
{
    public function __construct(private string $email)
    {
        $trimmed = trim($this->email);

        if ($trimmed === '') {
            throw new InvalidArgumentException('Email не может быть пустым.');
        }

        if (!filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(\sprintf('Неверный формат email: "%s".', $trimmed));
        }

        $this->email = mb_strtolower($trimmed);
    }

    public function toValue(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public static function fromValue(string $value): static
    {
        return new self($value);
    }

}
