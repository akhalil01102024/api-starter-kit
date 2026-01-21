<?php

namespace App\Actions\V1\Auth;

use Illuminate\Http\Request;

final readonly class LogoutAction
{
    public function execute(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
