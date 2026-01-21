<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Responses\HttpResponse;

class HomeController extends Controller
{
    public function __invoke(): HttpResponse
    {
        return HttpResponse::success(message: 'V1 Home');
    }
}
