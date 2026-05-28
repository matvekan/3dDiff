<?php

namespace App\Domain\ValueObject;

use Yokai\DoctrineValueObject\StringValueObject;

final class Token implements StringValueObject
{

    public function __construct(private string $token){}

    public static function fromValue(string $value): static
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->token;
    }

    public function toValue(): string
    {
        return $this->token;
    }
}
