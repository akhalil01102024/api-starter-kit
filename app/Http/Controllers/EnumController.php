<?php

namespace App\Http\Controllers;

// use App\Enums\Response\Status;
// use App\Http\Resources\V1\Common\BackedEnumResource;
use App\Responses\HttpResponse;

class EnumController extends Controller
{
    public function __invoke(): HttpResponse
    {
        return HttpResponse::success(data: [
            // 'statuses' => BackedEnumResource::collection(resource: Status::cases()),
        ]);
    }
}
