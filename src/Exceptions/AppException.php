<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppException extends Exception
{
    /**
     * @param $message
     * @param mixed $content
     * @param int $statusCode
     */
    public function __construct(
        protected       $message = "",
        protected mixed $content = null,
        protected int   $statusCode = Response::HTTP_BAD_REQUEST,
    ) {
        parent::__construct($this->message, $this->statusCode);
    }

    public function render(): JsonResponse
    {
        return ResponseHelper::error($this->message, $this->content, $this->statusCode);
    }

    /**
     * @return mixed
     */
    public function getContent(): mixed
    {
        return $this->content;
    }
}
