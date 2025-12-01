<?php

namespace App\EventSubscriber;

use App\Exceptions\AppException;
use App\Helpers\ResponseHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

readonly class AppExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof AppException) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'trace' => $exception->getTraceAsString(),
                ]
            );
        }

        $exceptionStatusCode = $this->getExceptionStatusCode($exception);

        $event->setResponse(
            ResponseHelper::error(
                $this->getExceptionMessage($exception, $exceptionStatusCode),
                content: $this->getExceptionContent($exception),
                statusCode: $exceptionStatusCode
            )
        );
    }

    /**
     * @param Throwable $exception
     * @param int $exceptionStatusCode
     * @return string
     */
    private function getExceptionMessage(Throwable $exception, int $exceptionStatusCode): string
    {
        return match ($exceptionStatusCode) {
            Response::HTTP_UNPROCESSABLE_ENTITY  => 'Validation error',
            Response::HTTP_NOT_FOUND             => 'Not found',
            Response::HTTP_INTERNAL_SERVER_ERROR => 'Something went wrong',
            Response::HTTP_METHOD_NOT_ALLOWED    => 'Method not allowed',
            default                              => $exception->getMessage()
        };
    }

    /**
     * @param Throwable $exception
     * @return int
     */
    private function getExceptionStatusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return match ($exception::class) {
            AppException::class => $exception->getCode(),
            default             => Response::HTTP_BAD_REQUEST
        };
    }

    /**
     * @param Throwable $exception
     * @return array|null
     */
    private function getExceptionContent(Throwable $exception): ?array
    {
        return $exception instanceof AppException ? $exception->getContent() : null;
    }
}
