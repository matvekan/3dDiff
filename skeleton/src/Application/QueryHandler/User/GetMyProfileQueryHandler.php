<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\User;

use App\Application\Dto\Factory\UserDtoFactory;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Query\User\GetMyProfileQuery;
use App\Application\Service\AuthenticatedUserResolver;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetMyProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private AuthenticatedUserResolver $userResolver,
        private UserDtoFactory $userDtoFactory,
    ) {
    }

    public function __invoke(GetMyProfileQuery $query): array
    {
        $user = $this->userResolver->fromToken($query->token);

        return $this->userDtoFactory->create($user)->jsonSerialize();
    }
}
