<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api\User;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\User\UpdateMyInterestsCommand;
use App\Domain\Entity\User;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/v1/me/interests', name: 'user.me.interests.update', methods: ['PUT'])]
#[OA\Tag(name: 'Users')]
#[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: UpdateMyInterestsCommand::class)))]
#[OA\Response(response: 200, description: 'Interests updated')]
#[OA\Response(response: 422, description: 'Validation error')]
class UpdateMyInterestsController
{
    public function __construct(private CommandBusInterface $commandBus){}

    public function __invoke(#[CurrentUser] User $user, #[MapRequestPayload] UpdateMyInterestsCommand $command): JsonResponse
    {
        return new JsonResponse($this->commandBus->execute(new UpdateMyInterestsCommand(
            $command->interestIds,
            (string) $user->getId()
        )));
    }
}
