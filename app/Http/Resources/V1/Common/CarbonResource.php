<?php

namespace App\Http\Resources\V1\Common;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property-read CarbonInterface $resource */
class CarbonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'timezone' => [
                'name' => $this->resource->timezone->getName(),
                'type' => $this->resource->timezone->getType(),
            ],
            'timestamp' => $this->resource->timestamp,
            'iso' => $this->resource->toIso8601String(),
            'string' => $this->resource->toString(),
            'human' => $this->resource->diffForHumans(),
        ];
    }
}
