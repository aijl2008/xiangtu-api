<?php

namespace App\Http\Controllers\Api\My;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return Helper::success(Video::query()
            ->withoutGlobalScopes(['status'])
            ->where('wechat_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     * @param VideoRequest $request
     * @return array
     */
    public function store(VideoRequest $request)
    {
        return Helper::success((new \App\Service\Video())->store($request->data(), $request->user('api')));
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     * @return array
     */
    public function show(Video $video)
    {
        return Helper::success($video->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Video $video
     * @return array
     */
    public function update(Request $request, Video $video)
    {
        $video->update($request->all());
        return Helper::success($video->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Video $video
     * @return array
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return Helper::success();
    }
}
