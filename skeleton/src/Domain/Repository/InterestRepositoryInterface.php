<?php

declare(strict_types=1);


namespace App\Domain\Repository;

use App\Domain\Entity\Interest;
use Symfony\Component\Uid\Uuid;

interface InterestRepositoryInterface
{
    public function save(Interest $interest): void;

    public function remove(Interest $interest): void;

    /**
     * @param Uuid $userId
     * @return array<Interest>
     */
    public function getByUserId(Uuid $userId): array;

}
