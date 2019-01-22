<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:37
 */

namespace App\Http\Controllers\My;


use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $view = view('my.videos.index');
        $video = $request->user('wechat')
            ->video()
            ->with('wechat')
            ->orderBy('id', 'desc');
        $view->with('rows', $video->simplePaginate());
        $view->with('status', (new Video())->getOption());
        $view->with('classification', $request->input('classification', 0));
        return $view;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create()
    {
        return view('my.videos.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param VideoRequest $request
     * @return array
     */
    public function store(VideoRequest $request)
    {
        return Helper::success(
            (new \App\Service\Video())
                ->store(
                    $request->data(),
                    $request->user('wechat')
                )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Video $video)
    {
        return view("videos.show")
            ->with('row', $video)
            ->with('related',
                Video::query()
                    ->where(
                        'wechat_id',
                        '<>',
                        $request->user('wechat')->id
                    )->take(4)
                    ->get()
            );
    }

    /**
     * @param Video $video
     * @return $this
     */
    public function edit(Video $video)
    {
        return view("my.videos.edit")->with("row", $video);
    }

    /**
     * @param VideoRequest $request
     * @param Video $video
     * @return array
     */
    public function update(VideoRequest $request, Video $video)
    {
        $video->update($request->data());
        return Helper::success();
    }

    /**
     * @param Video $video
     * @return array
     * @throws \Exception
     */
    public function destroy(Video $video)
    {
        $video->wechat()->decrement('uploaded_number', 1);
        $video->task()->delete();
        $video->followed()->delete();
        $video->liker()->detach();
        $video->delete();

        return Helper::success();
    }

    public function uploadCover(Request $request)
    {
        if ($request->ajax()) {
            $file = $request->file('cover');
            if (!$file) {
                return Helper::error(-1, "请选择图片" . var_export($_POST, true));
            }
            $path = $file->store('upload');
            return Helper::success(
                [
                    "url" => asset(Storage::url($path))
                ]
            );
        }
    }
}