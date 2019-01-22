<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:30
 */

namespace App\Http\Controllers\Api\My;


use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Service\Like;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LikeController extends Controller
{
    function index(Request $request)
    {
        return Helper::success(
            call_user_func(function (Authenticatable $user = null) {
                if (!$user) {
                    return new LengthAwarePaginator([], 0, 20);
                }
                return $user->liked()->with('wechat')->paginate(16);
            }, $request->user('api'))
        );
    }

    function store(Request $request)
    {
        return (new Like(
            $video = Video::query()->find($request->input('video_id')),
            $user = $request->user('api')
        ))->toggle($request);
    }

    function destroy(Request $request, $video_id)
    {
        return (new Like(
            Video::query()->find($video_id),
            $user = $request->user('api')
        ))->toggle($request);
    }
}


