<?php
declare(strict_types=1);

namespace App\Application\Command\Interest;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateInterestCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        public string $name,
    )
    {}
}
