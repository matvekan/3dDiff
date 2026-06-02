<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\PasswordReset\PasswordResetMailerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class PasswordResetMailer implements PasswordResetMailerInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string $fromEmail,
        private string $frontendResetUrl,
    ) {
    }

    public function sendResetLink(string $toEmail, string $token): void
    {
        $separator = str_contains($this->frontendResetUrl, '?') ? '&' : '?';
        $resetUrl = rtrim($this->frontendResetUrl, '/').$separator.'token='.urlencode($token);

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($toEmail)
            ->subject('Password reset')
            ->text("To reset password open link: {$resetUrl}")
            ->html("<p>To reset password open link:</p><p><a href=\"{$resetUrl}\">{$resetUrl}</a></p>");

        $this->mailer->send($email);
    }
}
