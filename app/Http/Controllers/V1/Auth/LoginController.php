<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Responses\HttpResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginAction $loginAction): HttpResponse
    {
        $result = $loginAction->execute(request: $request);

        return HttpResponse::success(
            message: 'Login successful.',
            data: [
                'user' => UserResource::make(resource: $result['user']),
                'token' => $result['token'],
            ]
        );
    }
}
