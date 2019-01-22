<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function index(Request $request)
    {
        return view('admin.logs.index')
            ->with(
                'rows',
                Log::query()
                    ->when($wechat_id = $request->input('wechat_id'), function (Builder $builder) use ($wechat_id) {
                        $builder->where('from_user_id', $wechat_id);
                    })
                    ->orderBy('id', 'desc')
                    ->simplePaginate()->appends($request->all())
            );
    }
}
