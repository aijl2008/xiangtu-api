<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index()
    {
        return redirect()->to(route('my.videos.index'));
    }
}
