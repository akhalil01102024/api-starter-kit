<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutAction;
use App\Http\Controllers\Controller;
use App\Responses\HttpResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request, LogoutAction $logoutAction): HttpResponse
    {
        $logoutAction->execute(request: $request);

        return HttpResponse::noContent();
    }
}
