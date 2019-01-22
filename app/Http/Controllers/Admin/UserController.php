<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wechat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index(Request $request)
    {
        return view('admin.users.index')
            ->with('rows', Wechat::query()
                ->when($nickname = $request->input('nickname'), function (Builder $builder) use ($nickname) {
                    $builder->where('nickname', 'like', '%' . $nickname . '%');
                })
                ->orderBy('id', 'desc')
                ->simplePaginate());
    }
}
