<?php

namespace App\DTO\User\Domain;

use App\Enums\RoleEnum;

class UserCreateData
{
    /**
     * @param string $login
     * @param string|null $phone
     * @param RoleEnum $role
     * @param string $password
     */
    public function __construct(
        public string   $login,
        public ?string  $phone,
        public RoleEnum $role,
        public string   $password,
    ) {
    }
}
