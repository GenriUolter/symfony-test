<?php

namespace App\Helpers;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * @param string|null $message
     * @param mixed|null $content
     * @param int $statusCode
     * @param array|null $pagination
     * @return JsonResponse
     */
    public static function success(
        ?string $message = null,
        mixed   $content = null,
        int     $statusCode = Response::HTTP_OK,
        ?array  $pagination = null,
    ): JsonResponse {
        return new JsonResponse(
            [
                'status'  => 'success',
                'message' => $message,
                'payload' => [
                    'content'    => $content,
                    'pagination' => $pagination,
                ],
            ],
            $statusCode
        );
    }

    /**
     * @param string|null $message
     * @param mixed|null $content
     * @param int $statusCode
     * @param array|null $pagination
     * @return JsonResponse
     */
    public static function error(
        ?string $message = null,
        mixed   $content = null,
        int     $statusCode = Response::HTTP_BAD_REQUEST,
        ?array  $pagination = null,
    ): JsonResponse {
        return new JsonResponse(
            [
                'status'  => 'error',
                'message' => $message,
                'payload' => [
                    'content'    => $content,
                    'pagination' => $pagination,
                ],
            ],
            $statusCode
        );
    }

    /**
     * @param string $message
     * @return never
     * @throws AppException
     */
    public static function serverError(string $message = 'Something went wrong'): never
    {
        throw new AppException(
            $message,
            statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * @param string $message
     * @return never
     * @throws AppException
     */
    public static function unauthorised(string $message = 'Unauthorised'): never
    {
        throw new AppException(
            $message,
            statusCode: Response::HTTP_UNAUTHORIZED
        );
    }
}
