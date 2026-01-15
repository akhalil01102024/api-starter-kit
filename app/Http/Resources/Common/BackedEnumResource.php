<?php

namespace App\Http\Resources\Common;

use App\Attributes\Enums\InResource;
use BackedEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;
use ReflectionMethod;

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

        $reflection = new ReflectionClass($this->resource);
        foreach ($reflection->getMethods(filter: ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(name: InResource::class);
            if (empty($attributes)) {
                continue;
            }

            /** @var InResource $attribute */
            $attribute = $attributes[0]->newInstance();
            $methodName = $method->getName();

            $data[$attribute->key ?? $methodName] = $this->resource->$methodName();
        }

        return $data;
    }
}
