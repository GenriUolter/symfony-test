<?php

namespace App\DTO\Auth\Domain;

use App\DTO\Auth\Request\LoginRequestDTO;

class AuthDataMapper
{
    /**
     * @param LoginRequestDTO $dto
     * @return AuthData
     */
    public function fromHttpRequestDto(LoginRequestDTO $dto): AuthData
    {
        return new AuthData(
            login: $dto->login,
            password: $dto->password,
        );
    }
}
