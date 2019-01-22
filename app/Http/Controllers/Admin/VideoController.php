<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:37
 */

namespace App\Http\Controllers\Admin;


use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Video;
use App\Models\Vod;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.videos.index')
            ->with('rows', Video::query()
                ->withoutGlobalScope('status')
                ->orderBy('id', 'desc')
                ->simplePaginate()
            )->with('status', (new Video())->getOption())
            ->with('visibilities', (new Video())->getVisibilitiesOptions());
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Video $video)
    {
        return view("admin.videos.show")
            ->with('row', $video);
    }

    public function snapshot(Video $video)
    {
        $snapshot = (new Vod())->createSnapshotByTimeOffset($video->file_id);
        Task::query()->create(
            [
                'file_id' => $video->file_id,
                'task_id' => $snapshot->vodTaskId,
                'code' => $snapshot->code,
                'code_desc' => $snapshot->codeDesc,
                'message' => $snapshot->message
            ]
        );
        if ($snapshot->code == 0) {
            return Helper::success(
                [
                    'vodTaskId' => $snapshot->vodTaskId
                ]
            );
        }
        return Helper::error(-1, $snapshot->message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function status(Request $request, $id)
    {
        try {
            $Video = Video::withoutGlobalScope('status')->findOrFail($id);
            $Video->update(['status' => $request->input('status')]);
            return redirect()->to(route('admin.videos.index'))->with('message', '成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.videos.index'))->with('message', '失败');
        }
    }
}