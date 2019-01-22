<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:37
 */

namespace App\Http\Controllers\My;


use App\Http\Controllers\Controller;
use App\Models\Wechat;
use App\Service\Follow;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function index(Request $request)
    {
        $view = view('my.followed.index');
        $view->with('recommended', Wechat::query()->has('video')->inRandomOrder()->limit(10)->get());
        $view->with('rows', $request->user('wechat')->followed(true)->with(
            [
                'video' => function (HasMany $query) {
                    return $query->orderBy('id', 'desc')->take(4);
                }
            ]
        )->simplePaginate());
        return $view;

    }

    function store(Request $request)
    {
        return (new Follow(
            Wechat::query()->find($request->input('wechat_id')),
            $request->user('wechat')
        ))->toggle($request);
    }
}