<?php

namespace App\DTO\User\Domain;

use App\DTO\User\Request\UserUpdateRequestDTO;

class UserUpdateDataMapper
{
    /**
     * @param UserUpdateRequestDTO $dto
     * @return UserUpdateData
     */
    public function fromHttpRequestDto(UserUpdateRequestDTO $dto): UserUpdateData
    {
        return new UserUpdateData(
            login: $dto->login,
            phone: $dto->phone,
            role: $dto->role,
            password: $dto->password,
        );
    }
}
