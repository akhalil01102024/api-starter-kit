<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

final readonly class LogoutAction
{
    public function execute(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
