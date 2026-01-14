<?php

namespace App\Responses;

use App\Enums\Response\Status;
use Error;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use ValueError;

final readonly class ExceptionResponse extends ApiResponse
{
    public static function fromError(Error $exception, Request $request): JsonResponse
    {
        return self::error(
            message: self::getMessage(exception: $exception),
            additionalData: self::generateExceptionDebugData(exception: $exception),
            status: Status::INTERNAL_SERVER_ERROR,
        )->toResponse(request: $request);
    }

    public static function fromException(Exception $exception): self
    {
        return match (true) {
            $exception instanceof AuthenticationException => self::fromAuthenticationException(exception: $exception),
            $exception instanceof ValidationException => self::fromValidationException(exception: $exception),
            default => self::fromDefault(exception: $exception),
        };
    }

    private static function fromAuthenticationException(AuthenticationException $exception): self
    {
        return self::error(
            message: $exception->getMessage(),
            status: Status::UNAUTHORIZED,
        );
    }

    private static function fromValidationException(ValidationException $exception): self
    {
        return self::error(
            message: $exception->getMessage(),
            additionalData: [
                'errors' => $exception->errors(),
            ],
            status: Status::UNPROCESSABLE_ENTITY,
        );
    }

    private static function fromDefault(Throwable $exception): self
    {
        $status = Status::INTERNAL_SERVER_ERROR;
        $headers = [];
        if ($exception instanceof HttpExceptionInterface) {
            $status = self::sanitizeStatus(status: $exception->getStatusCode());
            $headers = $exception->getHeaders();
        }

        return self::error(
            message: self::getMessage(exception: $exception),
            data: method_exists(object_or_class: $exception, method: 'context') ? $exception->context() : [],
            additionalData: self::generateExceptionDebugData(exception: $exception),
            status: $status,
            headers: $headers,
        );
    }

    private static function getMessage(Throwable $exception): string
    {
        if (app()->hasDebugModeEnabled()) {
            return $exception->getMessage();
        }

        return $exception instanceof HttpExceptionInterface ? $exception->getMessage() : __(key: 'responses.messages.server_error');
    }

    private static function generateExceptionDebugData(Throwable $exception): array
    {
        $exceptionDebugData = [];
        if (app()->hasDebugModeEnabled()) {
            $exceptionDebugData['exception'] = get_class(object: $exception);
            $exceptionDebugData['line'] = $exception->getLine();
            $exceptionDebugData['file'] = $exception->getFile();
            $exceptionDebugData['trace'] = collect(value: $exception->getTrace())
                ->map(
                    callback: static fn ($trace): array => Arr::except(array: $trace, keys: ['args']),
                )
                ->toArray();
        }

        return $exceptionDebugData;
    }

    private static function sanitizeStatus(string|int $status): Status
    {
        if (is_string(value: $status)) {
            return Status::INTERNAL_SERVER_ERROR;
        }

        if ($status < 100 || $status >= 600) {
            return Status::INTERNAL_SERVER_ERROR;
        }

        try {
            return Status::from(value: $status);
        } catch (ValueError) {
            return Status::INTERNAL_SERVER_ERROR;
        }
    }
}
