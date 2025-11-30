<?php

namespace App\Services;

use App\DTO\User\Domain\UserCreateData;
use App\DTO\User\Domain\UserUpdateData;
use App\Entity\User;
use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserService
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param LoggerInterface $logger
     */
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private LoggerInterface             $logger
    ) {
    }

    /**
     * @param UserCreateData $data
     * @return User
     * @throws AppException
     */
    public function create(UserCreateData $data): User
    {
        $this->logger->debug(
            'UserService: create user',
            [
                'login' => $data->login,
                'phone' => $data->phone,
                'role'  => $data->role,
            ]
        );

        try {
            $user = new User();
            $user->setLogin($data->login);
            $user->setPhone($data->phone);
            $user->setRole($data->role);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $data->password
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user;
        } catch (Exception $exception) {
            $this->logger->error(
                'UserService: create user error',
                [
                    'exception' => $exception->getMessage(),
                ]
            );

            ResponseHelper::serverError();
        }
    }

    /**
     * @param User $user
     * @param UserUpdateData $updateData
     * @return User
     * @throws AppException
     */
    public function update(User $user, UserUpdateData $updateData): User
    {
        $this->logger->debug(
            'UserService: update user',
            [
                'id'    => $user->getId(),
                'login' => $updateData->login,
                'phone' => $updateData->phone,
                'role'  => $updateData->role,
            ]
        );

        try {
            $user->setLogin($updateData->login);
            $user->setPhone($updateData->phone);
            $user->setRole($updateData->role);

            $this->entityManager->flush();

            return $user;
        } catch (Exception $exception) {
            $this->logger->error(
                'UserService: update user error',
                [
                    'exception' => $exception->getMessage(),
                ]
            );

            ResponseHelper::serverError();
        }
    }

    /**
     * @param User $user
     * @return void
     * @throws AppException
     */
    public function delete(User $user): void
    {
        $this->logger->debug(
            'UserService: delete user',
            [
                'id' => $user->getId(),
            ]
        );

        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (Exception $exception) {
            $this->logger->error(
                'UserService: delete user error',
                [
                    'exception' => $exception->getMessage(),
                ]
            );

            ResponseHelper::serverError();
        }
    }
}
