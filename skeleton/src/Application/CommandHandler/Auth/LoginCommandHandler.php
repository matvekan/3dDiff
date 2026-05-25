<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\LoginCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\AuthTokenService;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

#[AsMessageHandler]
final readonly class LoginCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private AuthTokenService $tokenService,
    ) {
    }

    public function __invoke(LoginCommand $command): array
    {
        try {
            $user = $this->userRepository->getByEmail($command->email);
        } catch (UserNotFoundException) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid credentials');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid credentials');
        }

        return [
            'token' => $this->tokenService->createForUser((string) $user->getId(), (string) $user->getRole()),
            'role' => $user->getRole(),
            'userId' => (string) $user->getId(),
        ];
    }
}
