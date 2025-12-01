<?php

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class JwtTokenService
{
    /**
     * @param string $secret
     * @param int $ttl
     */
    public function __construct(
        private string $secret,
        private int    $ttl
    ) {
    }

    /**
     * @param UserInterface $user
     * @return array
     */
    public function createToken(UserInterface $user): array
    {
        $now = time();
        $exp = $now + $this->ttl;

        $payload = [
            'sub'   => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'iat'   => $now,
            'exp'   => $exp,
        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

        return [
            'token' => $token,
            'exp'   => $exp,
        ];
    }

    /**
     * @param string $token
     * @return array
     */
    public function parseToken(string $token): array
    {
        return (array)JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
