<?php

namespace App\Concerns\Response;

use App\Enums\Response\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait Responsable
{
    public function __construct(
        public readonly array $data = [],
        public readonly Status $status = Status::OK,
        public readonly array $headers = [],
    ) {}

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->data,
            status: $this->status->value,
            headers: $this->headers,
        );
    }

    public static function make(
        bool $success,
        string $message,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        Status $status = Status::OK,
        array $headers = [],
    ): static {
        return new static(
            data: [
                'success' => $success,
                'code' => $status->value,
                'message' => $message,
                'data' => $data ?? [],
                ...$additionalData,
            ],
            status: $status,
            headers: $headers,
        );
    }

    public static function success(
        ?string $message = null,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        Status $status = Status::OK,
        array $headers = [],
    ): static {
        return static::make(
            success: true,
            message: $message ?? __(key: 'response.messages.success'),
            data: $data,
            additionalData: $additionalData,
            status: $status,
            headers: $headers,
        );
    }

    public static function error(
        ?string $message = null,
        null|array|Model|JsonResource $data = [],
        array $additionalData = [],
        Status $status = Status::BAD_REQUEST,
        array $headers = [],
    ): static {
        return static::make(
            success: false,
            message: $message ?? __(key: 'response.messages.error'),
            data: $data,
            additionalData: $additionalData,
            status: $status,
            headers: $headers,
        );
    }
}
