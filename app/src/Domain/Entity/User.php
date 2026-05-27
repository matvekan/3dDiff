<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Role;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users', uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_users_email', columns: ['email'])])]
#[UniqueEntity(fields: ['email'], message: 'Email already used.')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: "name",length: 255)]
    private Name $name;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: Role::class)]
    private ?Role $role = Role::USER;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: "email")]
    private Email $email;

    /**
     * @var Collection<int, Interest>
     */
    #[ORM\ManyToMany(targetEntity: Interest::class, inversedBy: 'users')]
    private Collection $interest;

    public function __construct()
    {
        $this->interest = new ArrayCollection();
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role->value;
    }

    public function setRole(string $role): static
    {
        $this->role = Role::from($role);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        if (null !== $this->role) {
            $roles[] = $this->role->value;
        }

        return array_values(array_unique($roles));
    }

    /**
     * @param array<int, string> $roles
     */
    public function setRoles(array $roles): static
    {
        /** @var array<int, string> $roles */
        $role = $roles[0] ?? 'ROLE_USER';
        $this->setRole($role);

        return $this;
    }

    public function getUserIdentifier(): string
    {
        if (!isset($this->email)) {
            throw new \LogicException('User email must be set before authentication.');
        }

        return $this->email->toValue();
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Interest>
     */
    public function getInterest(): Collection
    {
        return $this->interest;
    }

    public function addInterest(Interest $interest): static
    {
        if (!$this->interest->contains($interest)) {
            $this->interest->add($interest);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): static
    {
        $this->interest->removeElement($interest);

        return $this;
    }


    public function getInterestIds(): array
    {
        return $this->interest->map(static fn (Interest $interest) => (string) $interest->getId())->toArray();
    }
}
