<?php

namespace App\Http\Controllers\Wechat\OfficialAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Wechat\OfficialAccount\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("wechat.official_account.promotions.index")
            ->with('rows', Promotion::query()
                ->orderBy('id', 'desc')
                ->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create()
    {
        return view('wechat.official_account.promotions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionRequest $request)
    {
        try {
            $Promotion = new Promotion($request->data());
            $Promotion->save();
            return redirect()->to(route('wechat.official_account.promotions.index'))->with('message', '添加成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('wechat.official_account.promotions.index'))->with('message', '添加失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param Promotion $Promotion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Promotion $Promotion)
    {
        return view('wechat.official_account.promotions.edit')
            ->with('row', $Promotion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionRequest $request, $id)
    {
        try {
            $Promotion = Promotion::query()->findOrFail($id);
            $Promotion->update(
                $request->data()
            );
            return redirect()->to(route('wechat.official_account.promotions.index'))->with('message', '修改成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('wechat.official_account.promotions.index'))->with('message', '修改失败');
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
            $Promotion = Promotion::query()->findOrFail($id);
            $Promotion->delete();
            return redirect()->to(route('admin.Promotion.index'))->with('message', '删除成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.Promotion.index'))->with('message', '删除失败');
        }
    }
}
