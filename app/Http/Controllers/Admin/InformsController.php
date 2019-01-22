<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inform;
use Illuminate\Http\Request;

class InformsController extends Controller
{
    function index(Request $request)
    {
        return view('admin.informs.index')
            ->with(
                'rows',
                Inform::query()
                    ->with('video')
                    ->with('wechat')
                    ->orderBy('id', 'desc')
                    ->simplePaginate()->appends($request->all())
            );
    }
}
