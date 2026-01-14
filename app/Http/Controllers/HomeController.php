<?php

namespace App\Http\Controllers;

use App\Responses\HttpResponse;

class HomeController extends Controller
{
    public function __invoke(): HttpResponse
    {
        return HttpResponse::success(message: 'Home page loaded.');
    }
}
