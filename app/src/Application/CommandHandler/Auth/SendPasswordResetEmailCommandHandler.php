<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\SendPasswordResetEmailCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\PasswordResetMailer;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendPasswordResetEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordResetMailer $passwordResetMailer,
    ) {}

    public function __invoke(SendPasswordResetEmailCommand $command): void
    {
        $this->passwordResetMailer->sendResetLink($command->email, $command->token);
    }
}
