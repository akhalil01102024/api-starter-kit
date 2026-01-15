<?php

namespace App\Http\Resources\Common;

use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property-read BackedEnum $resource */
class BackedEnumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'value' => $this->resource->value,
        ];

        if (method_exists(object_or_class: $this->resource, method: 'label')) {
            $data['label'] = $this->resource->label();
        }

        return $data;
    }
}
