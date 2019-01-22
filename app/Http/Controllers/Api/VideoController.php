<?php

namespace App\Http\Controllers\Api;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return Helper::success(
            (new \App\Service\Video())
                ->paginate(
                    $request->user("api"),
                    $request->input('classification'),
                    $request->input('wechat_id'),
                    16,
                    false,
		    $request->input('video_id')
                )
        );
    }

    function show(Request $request, $id)
    {
	$video = Video::query()->findOrFail(intval($id));
        $video->increment('played_number');
        $user = $request->user('api');
        $video->wechat()->increment('played_number');
        (new Log())->setRequest($request)->log("播放", $user ? $user->id : 0, $video->wechat->id, $video->id);

        return Helper::success((new \App\Service\Video())->show($video, Auth::guard('api')->user()));
    }
}
