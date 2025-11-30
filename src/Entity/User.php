<?php

namespace App\Entity;

use App\Enums\RoleEnum;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_login_phone', fields: ['login', 'phone'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 8, unique: true)]
    private ?string $login = null;

    /**
     * @var RoleEnum|null
     */
    #[ORM\Column]
    private ?RoleEnum $role = null;

    /**
     * @var string|null
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 8, unique: true, nullable: true)]
    private ?string $phone = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return $this
     */
    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRole(): ?RoleEnum
    {
        return $this->role;
    }

    /**
     * @param RoleEnum $role
     * @return $this
     */
    public function setRole(RoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {
        if ($this->role === null) {
            return [RoleEnum::User->value];
        }

        return [$this->role->value];
    }
}
