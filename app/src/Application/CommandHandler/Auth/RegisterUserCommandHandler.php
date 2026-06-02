<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Auth;

use App\Application\Command\Auth\RegisterUserCommand;
use App\Application\Command\CommandHandlerInterface;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Role;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler]
final readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $users,
        private UserPasswordHasherInterface $passwordHasher,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        try {
            $this->users->getByEmail($command->email);
            throw new ConflictHttpException('Email already used.');
        } catch (UserNotFoundException) {
        }

        $user = new User();
        $user->updateName(new Name($command->name));
        $user->updateEmail(new Email($command->email));
        $user->updateCreatedAt(new \DateTimeImmutable());
        $user->updateRole(Role::USER->value);
        $user->updatePassword($this->passwordHasher->hashPassword($user, $command->password));

        $this->users->save($user);
        $this->cache->invalidateTags(['users_list']);
    }
}
