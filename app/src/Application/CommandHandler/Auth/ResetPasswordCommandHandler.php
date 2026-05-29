<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\ResetPasswordCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Domain\Repository\PasswordResetRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

#[AsMessageHandler]
final readonly class ResetPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(ResetPasswordCommand $command): void
    {
        $reset = $this->passwordResetRepository->findValidByToken($command->token);
        if (null === $reset) {
            throw new BadRequestHttpException('Invalid or expired token');
        }

        try {
            $user = $this->userRepository->getByEmail((string) $reset->getEmail());
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException('User not found');
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->newPassword));
        $reset->setUsedAt(new \DateTimeImmutable());

        $this->userRepository->save($user);
        $this->passwordResetRepository->save($reset);
    }
}
