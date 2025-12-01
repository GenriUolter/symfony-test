<?php

namespace App\Controller;

use App\DTO\Auth\Domain\AuthDataMapper;
use App\DTO\Auth\Request\LoginRequestDTO;
use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use App\Services\AuthService;
use App\Validation\ValidationErrorMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    /**
     * @param ValidatorInterface $validator
     * @param ValidationErrorMapper $validationErrorMapper
     * @param AuthService $authService
     * @param AuthDataMapper $authDataMapper
     */
    public function __construct(
        private readonly ValidatorInterface    $validator,
        private readonly ValidationErrorMapper $validationErrorMapper,
        private readonly AuthService           $authService,
        private readonly AuthDataMapper        $authDataMapper,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AppException
     */
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $payload         = $request->getPayload();
        $loginRequestDTO = new LoginRequestDTO(
            login: $payload->get('login'),
            password: $payload->get('password'),
        );

        $this->validationErrorMapper->throwIfNotValid(
            $this->validator->validate($loginRequestDTO)
        );

        return ResponseHelper::success(
            content: $this->authService->login(
                $this->authDataMapper->fromHttpRequestDto(
                    $loginRequestDTO
                )
            )
        );
    }
}
