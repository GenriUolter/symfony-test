<?php

namespace App\DTO\Auth\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDTO
{
    /**
     * @param string|null $login
     * @param string|null $password
     */
    public function __construct(
        #[Assert\NotBlank]
        public ?string $login = null,
        #[Assert\NotBlank]
        public ?string $password = null
    ) {
    }
}
