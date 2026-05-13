<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[UniqueEntity(fields: ['email'], message: 'Email already used.')]
class User extends AbstractEntity implements UserInterface
{
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    private ?string $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, Interest>
     */
    #[ORM\ManyToMany(targetEntity: Interest::class, inversedBy: 'users')]
    private Collection $interest;

    public function __construct()
    {
        $this->interest = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
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
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = strtoupper($role);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        if (null !== $this->role && '' !== $this->role) {
            $roles[] = $this->role;
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
        if (null === $this->email || '' === $this->email) {
            throw new \LogicException('User email must be set before authentication.');
        }

        return $this->email;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

}
