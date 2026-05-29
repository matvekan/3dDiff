<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\ForgotPasswordCommand;
use App\Application\Command\Auth\SendPasswordResetEmailCommand;
use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandHandlerInterface;
use App\Domain\Entity\PasswordReset;
use App\Domain\Repository\PasswordResetRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Token;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

#[AsMessageHandler]
final readonly class ForgotPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(ForgotPasswordCommand $command): void
    {
        try {
            $this->userRepository->getByEmail($command->email);
        } catch (UserNotFoundException) {
            return;
        }

        $token = bin2hex(random_bytes(32));
        $passwordReset = new PasswordReset()
            ->setEmail(new Email($command->email))
            ->setToken(new Token($token))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setExpiresAt(new \DateTimeImmutable()->add(new \DateInterval('PT1H')));

        $this->passwordResetRepository->save($passwordReset);
        $this->commandBus->execute(new SendPasswordResetEmailCommand($command->email, $token));
    }
}
