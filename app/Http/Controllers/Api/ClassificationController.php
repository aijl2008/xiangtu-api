<?php

namespace App\Http\Controllers\Api;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Classification;

class ClassificationController extends Controller
{
    function index()
    {
        return Helper::success(Classification::query()->get());
    }
}
