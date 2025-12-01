<?php

namespace App\Controller;

use App\DTO\User\Domain\UserCreateDataMapper;
use App\DTO\User\Domain\UserUpdateDataMapper;
use App\DTO\User\Request\UserCreateRequestDTO;
use App\DTO\User\Request\UserUpdateRequestDTO;
use App\Entity\User;
use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use App\Resources\UserResource;
use App\Services\UserService;
use App\Validation\ValidationErrorMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
final class UserController extends AbstractController
{
    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'app_user_show', methods: ['GET', 'HEAD'])]
    #[IsGranted('view', 'user')]
    public function show(User $user): JsonResponse
    {
        return ResponseHelper::success(
            content: UserResource::fromEntity($user)->toArray()
        );
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @param ValidatorInterface $validator
     * @param ValidationErrorMapper $validationErrorMapper
     * @param UserCreateDataMapper $userCreateDataMapper
     * @return JsonResponse
     * @throws AppException
     */
    #[Route('', name: 'app_user_create', methods: ['POST'])]
    #[IsGranted('create')]
    public function create(
        Request               $request,
        UserService           $userService,
        ValidatorInterface    $validator,
        ValidationErrorMapper $validationErrorMapper,
        UserCreateDataMapper  $userCreateDataMapper
    ): JsonResponse {
        $payload              = $request->getPayload();
        $userCreateRequestDTO = new UserCreateRequestDTO(
            login: $payload->get('login'),
            phone: $payload->get('phone'),
            role: $payload->get('role'),
            password: $payload->get('password'),
        );

        $validationErrorMapper->throwIfNotValid(
            $validator->validate($userCreateRequestDTO)
        );

        return ResponseHelper::success(
            content: UserResource::fromEntity(
                $userService->create(
                    $userCreateDataMapper->fromHttpRequestDto($userCreateRequestDTO)
                )
            )->toArray(),
            statusCode: Response::HTTP_CREATED
        );
    }

    /**
     * @param User $user
     * @param Request $request
     * @param UserService $userService
     * @param ValidatorInterface $validator
     * @param ValidationErrorMapper $validationErrorMapper
     * @param UserUpdateDataMapper $userUpdateDataMapper
     * @return JsonResponse
     * @throws AppException
     */
    #[Route('/{id}', name: 'app_user_update', methods: ['PUT'])]
    #[IsGranted('update', 'user')]
    public function update(
        User                  $user,
        Request               $request,
        UserService           $userService,
        ValidatorInterface    $validator,
        ValidationErrorMapper $validationErrorMapper,
        UserUpdateDataMapper  $userUpdateDataMapper
    ): JsonResponse {
        $payload              = $request->getPayload();
        $userUpdateRequestDTO = new UserUpdateRequestDTO(
            login: $payload->get('login'),
            phone: $payload->get('phone'),
            role: $payload->get('role'),
            password: $payload->get('password'),
        );

        $validationErrorMapper->throwIfNotValid(
            $validator->validate($userUpdateRequestDTO)
        );

        return ResponseHelper::success(
            content: UserResource::fromEntity(
                $userService->update(
                    $user,
                    $userUpdateDataMapper->fromHttpRequestDto($userUpdateRequestDTO)
                )
            )->toArray()
        );
    }

    /**
     * @param User $user
     * @param UserService $userService
     * @return JsonResponse
     * @throws AppException
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    #[IsGranted('delete')]
    public function delete(
        User        $user,
        UserService $userService,
    ): JsonResponse {
        /** @var User $authUser */
        $authUser = $this->getUser();

        if ($user->getId() === $authUser->getId()) {
            throw new AppException('You cannot delete yourself');
        }

        $userService->delete($user);

        return ResponseHelper::success();
    }
}
