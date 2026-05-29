<?php
declare(strict_types=1);

namespace App\Application\Command\User;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateMyInterestsCommand implements CommandInterface
{
    public function __construct(
        /** @var array<string> */
        #[Assert\NotNull]
        #[Assert\Type('array')]
        #[Assert\All([new Assert\Uuid()])]
        public array $interestIds,
        #[Assert\Uuid]
        public string $userId = '',
    )
    {}
}
