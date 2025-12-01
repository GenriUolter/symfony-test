<?php

namespace App\DTO\Auth\Domain;

class AuthData
{
    /**
     * @param string $login
     * @param string $password
     */
    public function __construct(
        public string $login,
        public string $password
    ) {
    }
}
