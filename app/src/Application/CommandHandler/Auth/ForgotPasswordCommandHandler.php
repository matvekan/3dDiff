<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\ForgotPasswordCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\PasswordResetMailer;
use App\Domain\Entity\PasswordReset;
use App\Domain\Repository\PasswordResetRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Token;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

#[AsMessageHandler]
final readonly class ForgotPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private PasswordResetMailer $passwordResetMailer,
    ) {
    }

    public function __invoke(ForgotPasswordCommand $command): array
    {
        try {
            $this->userRepository->getByEmail($command->email);
        } catch (UserNotFoundException) {
            return ['message' => 'If account exists, reset instructions were sent to email'];
        }

        $token = bin2hex(random_bytes(32));
        $passwordReset = (new PasswordReset())
            ->setEmail(new Email($command->email))
            ->setToken(new Token($token))
            ->setCreatedAt(new DateTimeImmutable())
            ->setExpiresAt((new DateTimeImmutable())->add(new DateInterval('PT1H')));

        $this->passwordResetRepository->save($passwordReset);
        $this->passwordResetMailer->sendResetLink($command->email, $token);

        return ['message' => 'If account exists, reset instructions were sent to email'];
    }
}
