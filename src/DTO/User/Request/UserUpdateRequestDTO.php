<?php

namespace App\DTO\User\Request;

use App\Entity\User;
use App\Enums\RoleEnum;
use App\Validation\Constraints\UniqueUserLoginAndPhone;
use App\Validation\Constraints\UserRole;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: 'login',
    message: 'User with login {{ value }} already exists.',
    entityClass: User::class,
    errorPath: 'login')
]
#[UniqueEntity(
    fields: 'phone',
    message: 'User with phone {{ value }} already exists.',
    entityClass: User::class,
    errorPath: 'phone')
]
#[UniqueUserLoginAndPhone]
class UserUpdateRequestDTO
{
    /**
     * @param string|null $login
     * @param string|null $phone
     * @param RoleEnum|string|null $role
     * @param string|null $password
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 8)]
        public ?string              $login = null,
        #[Assert\NotBlank]
        #[Assert\Length(max: 8)]
        public ?string              $phone = null,
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
