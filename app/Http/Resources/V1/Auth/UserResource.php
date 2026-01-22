<?php

namespace App\Http\Resources\V1\Auth;

use App\Http\Resources\V1\Common\CarbonResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property-read User $resource */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'email_verified_at' => CarbonResource::make(resource: $this->resource->email_verified_at),
            'created_at' => CarbonResource::make(resource: $this->resource->created_at),
            'updated_at' => CarbonResource::make(resource: $this->resource->updated_at),
        ];
    }
}
