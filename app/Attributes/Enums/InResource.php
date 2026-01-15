<?php

namespace App\Attributes\Enums;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class InResource
{
    /**
     * @param  string|null  $key  Optional custom key name for the response. If not provided, the method name will be used.
     */
    public function __construct(
        public ?string $key = null,
    ) {}
}
