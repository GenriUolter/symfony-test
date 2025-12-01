<?php

namespace App\DTO\User\Domain;

use App\DTO\User\Console\UserAdminConsoleCreateDTO;
use App\DTO\User\Request\UserCreateRequestDTO;

class UserCreateDataMapper
{
    /**
     * @param UserCreateRequestDTO $dto
     * @return UserCreateData
     */
    public function fromHttpRequestDto(UserCreateRequestDTO $dto): UserCreateData
    {
        return new UserCreateData(
            login: $dto->login,
            phone: $dto->phone,
            role: $dto->role,
            password: $dto->password,
        );
    }

    /**
     * @param UserAdminConsoleCreateDTO $dto
     * @return UserCreateData
     */
    public function fromConsoleDto(UserAdminConsoleCreateDTO $dto): UserCreateData
    {
        return new UserCreateData(
            login: $dto->login,
            phone: null,
            role: $dto->role,
            password: $dto->password,
        );
    }
}
