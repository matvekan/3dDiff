<?php
declare(strict_types=1);

namespace App\Application\Command\Interest;

use App\Application\Command\CommandInterface;

final readonly class DeleteInterestCommand implements CommandInterface
{
    public function __construct(
        public string $adminId = '',
        public string $interestId = '',
    ) {}
}
