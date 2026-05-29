<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\Interest;

interface InterestRepositoryInterface
{
    public function save(Interest $interest): void;

    public function remove(Interest $interest): void;

    public function getById(string $id): Interest;

    /**
     * @param array<string> $ids
     * @return array<Interest>
     */
    public function findByIds(array $ids): array;

    /**
     * @return array<Interest>
     */
    public function findAllOrderedByName(): array;

}
