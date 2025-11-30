<?php

namespace App\Security;

use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Throwable;

class JwtAuthenticator extends AbstractAuthenticator
{
    /**
     * @param JwtTokenService $jwtTokenService
     * @param UserRepository $userRepository
     */
    public function __construct(
        private readonly JwtTokenService $jwtTokenService,
        private readonly UserRepository  $userRepository
    ) {
    }

    /**
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        $authHeader = $request->headers->get('Authorization');

        return is_string($authHeader) && str_starts_with($authHeader, 'Bearer ');
    }

    /**
     * @param Request $request
     * @return Passport
     * @throws AppException
     */
    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization', '');
        $token      = trim(substr($authHeader, 7));

        if (empty($token)) {
            throw new AppException(
                'Token required',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $payload = $this->jwtTokenService->parseToken($token);
        } catch (Throwable $exception) {
            throw new AppException(
                'Invalid token',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        $userIdentifier = $payload['sub'] ?? null;

        if (empty($userIdentifier)) {
            throw new AppException(
                'Invalid token',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }

        return new SelfValidatingPassport(
            new UserBadge($userIdentifier, function (string $identifier) {
                return $this->userRepository->findOneBy(['login' => $identifier]);
            })
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return ResponseHelper::error(
            'Toke expired or invalid',
            statusCode: Response::HTTP_UNAUTHORIZED
        );
    }
}
