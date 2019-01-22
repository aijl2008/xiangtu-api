<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;

class EventController extends Controller
{
    function index()
    {
        return view('admin.events.index')->with('rows', Event::query()->orderBy('id', 'desc')->simplePaginate());
    }
}
