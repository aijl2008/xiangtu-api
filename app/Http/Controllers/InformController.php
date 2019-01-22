<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\InformRequest;
use App\Models\Inform;
use Illuminate\Http\Request;

class InformController extends Controller
{
    function __invoke(InformRequest $request)
    {
        Inform::query()->create($request->data());
        return Helper::success();
    }
}
