<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Json::encodeUsing(
            encoder: static fn (mixed $value, int $flags): false|string => json_encode(value: $value, flags: JSON_UNESCAPED_UNICODE),
        );
    }
}
