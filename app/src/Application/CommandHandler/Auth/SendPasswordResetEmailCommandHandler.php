<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\SendPasswordResetEmailCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Domain\PasswordReset\PasswordResetMailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendPasswordResetEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordResetMailerInterface $passwordResetMailer,
    ) {
    }

    public function __invoke(SendPasswordResetEmailCommand $command): void
    {
        $this->passwordResetMailer->sendResetLink($command->email, $command->token);
    }
}
