<?php

namespace App\DTO\User\Console;

use App\Entity\User;
use App\Enums\RoleEnum;
use App\Validation\Constraints\UserRole;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: 'login',
    message: 'User with login {{ value }} already exists.',
    entityClass: User::class,
    errorPath: 'login')
]
class UserAdminConsoleCreateDTO
{
    /**
     * @param string|null $login
     * @param RoleEnum|string|null $role
     * @param string|null $password
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 8)]
        public ?string              $login = null,
        #[Assert\NotBlank]
        #[UserRole]
        public RoleEnum|string|null $role = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 8)]
        public ?string              $password = null
    ) {
        if ($this->role && !$this->role instanceof RoleEnum) {
            $this->role = RoleEnum::getRoleByKey($this->role);
        }
    }
}
