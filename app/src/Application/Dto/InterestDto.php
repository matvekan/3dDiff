<?php

declare(strict_types=1);

namespace App\Application\Dto;

class InterestDto implements \JsonSerializable
{
    public function __construct(
        private string $name,
        private string $id,
    )
    {}


    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name'=>$this->name,
            'id'=> $this->id
        ];
    }
}
