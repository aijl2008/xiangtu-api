<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Log;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{

    function index(Request $request)
    {
        $view = view('videos.index');
        $view->with(
            'rows',
            (new \App\Service\Video())
                ->paginate(
                    $request->user('wechat'),
                    $request->input('classification'),
                    0,
                    15
                )
        );
        $view->with('classification', $request->input('classification', 0));
        return $view;
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(\App\Service\Video $videoService, Video $video)
    {
        return view("videos.show")
            ->with('row', $videoService->show($video, Auth::guard('wechat')->user()))
            ->with('related',
                Video::query()
                    ->where(
                        'classification_id',
                        $video->classification_id
                    )
                    ->where('id', '<>', $video->id)
                    ->take(4)
                    ->get()
            );
    }

    /**
     * @param Request $request
     * @param Video $video
     * @return array
     */
    public function play(Request $request, Video $video)
    {
        $video->increment('played_number');
        $user = Auth::guard('wechat')->user();
        $video->wechat()->increment('played_number');
        (new Log())->setRequest($request)->log("播放", $user ? $user->id : 0, $video->wechat->id, $video->id);
        return Helper::success();
    }

    /**
     * @param Request $request
     * @param Video $video
     * @return array
     */
    public function shareToWechat(Request $request, Video $video)
    {
        $video->increment('shared_wechat_number');
        $user = Auth::guard('wechat')->user();
        (new Log())->setRequest($request)->log("分享到聊天", $user ? $user->id : 0, $video->wechat->id, $video->id, '');
        return Helper::success();
    }

    /**
     * @param Request $request
     * @param Video $video
     * @return array
     */
    public function shareToMoment(Request $request, Video $video)
    {
        $video->increment('shared_moment_number');
        $user = Auth::guard('wechat')->user();
        (new Log())->setRequest($request)->log("分享到朋友圈", $user ? $user->id : 0, $video->wechat->id, $video->id);
        return Helper::success();
    }
}
