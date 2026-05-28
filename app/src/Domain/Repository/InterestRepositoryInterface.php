<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\Interest;

interface InterestRepositoryInterface
{
    public function save(Interest $interest): void;

    public function remove(Interest $interest): void;

    public function getById(string $id): Interest;

    public function findByIds(array $ids): array;

    public function findAllOrderedByName(): array;

}
