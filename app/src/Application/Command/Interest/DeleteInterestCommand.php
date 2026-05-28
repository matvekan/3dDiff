<?php
declare(strict_types=1);

namespace App\Application\Command\Interest;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteInterestCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $interestId = '',
    )
    {}
}
