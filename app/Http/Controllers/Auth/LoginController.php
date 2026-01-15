<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserResource;
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
