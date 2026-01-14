<?php

namespace App\Responses;

use App\Enums\Response\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

final readonly class HttpResponse extends ApiResponse
{
    public static function noContent(): self
    {
        return new self(status: Status::NO_CONTENT);
    }

    public static function unauthenticated(
        ?string $message = null,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        array $headers = [],
    ): self {
        return self::error(
            message: $message ?? __(key: 'responses.messages.unauthenticated'),
            data: $data,
            additionalData: $additionalData,
            status: Status::UNAUTHORIZED,
            headers: $headers,
        );
    }

    public static function unauthorized(
        ?string $message = null,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        array $headers = [],
    ): self {
        return self::error(
            message: $message ?? __(key: 'responses.messages.unauthorized'),
            data: $data,
            additionalData: $additionalData,
            status: Status::FORBIDDEN,
            headers: $headers,
        );
    }

    public static function notfound(
        ?string $message = null,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        array $headers = [],
    ): self {
        return self::error(
            message: $message ?? __(key: 'responses.messages.notfound'),
            data: $data,
            additionalData: $additionalData,
            status: Status::NOT_FOUND,
            headers: $headers,
        );
    }

    public static function successWithPaginator(
        LengthAwarePaginator $paginator,
        ?string $message = null,
        null|array|Model|JsonResource $data = null,
        array $additionalData = [],
        Status $status = Status::OK,
        array $headers = [],
    ): self {
        return self::success(
            message: $message,
            data: $data ?? $paginator->items(),
            additionalData: [
                'links' => [
                    'first' => $paginator->url(page: 1),
                    'last' => $paginator->url(page: $paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'from' => $paginator->firstItem(),
                    'last_page' => $paginator->lastPage(),
                    'links' => $paginator->linkCollection(),
                    'path' => $paginator->path(),
                    'per_page' => $paginator->perPage(),
                    'to' => $paginator->lastItem(),
                    'total' => $paginator->total(),
                ],
                ...$additionalData,
            ],
            status: $status,
            headers: $headers,
        );
    }
}
