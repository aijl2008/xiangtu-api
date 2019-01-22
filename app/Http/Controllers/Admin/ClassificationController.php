<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassificationRequest;
use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param $video
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.classifications.index')
            ->with('rows', Classification::query()->get())
            ->with('status', (new Classification())->getStatusOption());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create()
    {
        return view('admin.classifications.create')->with('status', (new Classification())->getStatusOption());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $classification = new Classification($request->data());
            $classification->save();
            return redirect()->to(route('admin.classifications.index'))->with('message', '添加成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.classifications.index'))->with('message', '添加失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param Classification $classification
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Classification $classification)
    {
        return view('admin.classifications.edit')
            ->with('row', $classification)
            ->with('status', (new Classification())->getStatusOption());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassificationRequest $request, $id)
    {
        try {
            $classification = Classification::query()->findOrFail($id);
            $classification->update(
                $request->data()
            );
            return redirect()->to(route('admin.classifications.index'))->with('message', '修改成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.classifications.index'))->with('message', '修改失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $classification = Classification::query()->findOrFail($id);
            $classification->delete();
            return redirect()->to(route('admin.classification.index'))->with('message', '删除成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.classification.index'))->with('message', '删除失败');
        }
    }
}
