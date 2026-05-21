<?php

namespace App\Application\CommandHandler\User;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\User\RegisterUserCommand;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {}

    public function __invoke( RegisterUserCommand $command): void
    {
        $user = new User();
        $user->setName(new Name($command->name));
        $user->setEmail(new Email($command->email));
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setRole("ROLE_USER");

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->password));

        $this->userRepository->save($user);
    }
}
