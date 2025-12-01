<?php

namespace App\Services;

use App\DTO\Auth\Domain\AuthData;
use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use App\Repository\UserRepository;
use App\Resources\UserAuthResource;
use App\Security\JwtTokenService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class AuthService
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param JwtTokenService $jwtTokenService
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private JwtTokenService             $jwtTokenService,
        private UserRepository              $userRepository
    ) {
    }

    /**
     * @param AuthData $authData
     * @return array
     * @throws AppException
     */
    public function login(AuthData $authData): array
    {
        $user = $this->userRepository->findOneBy([
            'login' => $authData->login,
        ]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $authData->password)) {
            ResponseHelper::unauthorised('Invalid credentials');
        }

        $tokenData = $this->jwtTokenService->createToken($user);

        return [
            'token'      => $tokenData['token'],
            'expires_at' => $tokenData['exp'],
            'user'       => UserAuthResource::fromEntity($user)->toArray()
        ];
    }
}
