<?php

namespace App\Http\Controllers\Admin;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.messages.index')
            ->with('rows',
                Message::query()
                    ->where('to_user_name', config('wechat.original_id'))
                    ->orderBy('id', 'desc')
                    ->simplePaginate()
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create()
    {
        return view('admin.messages.create')->with('status', (new Message())->getStatusOption());
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
            $Message = new Message($request->data());
            $Message->save();
            return redirect()->to(route('admin.messages.index'))->with('message', '添加成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.messages.index'))->with('message', '添加失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param Message $Message
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Message $Message)
    {
        return view('admin.messages.edit')
            ->with('row', $Message)
            ->with('status', (new Message())->getStatusOption());
    }

    /**
     * @param MessageRequest $request
     * @param Message $message
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function update(MessageRequest $request, Message $message)
    {
        $config = config('wechat.mini_program.default');
        $to = str_replace($config['app_id'] . '|', '', $message->from_user_name);
        $app = Factory::miniProgram($config);
        $content = $request->input('content');
        $app->server->push(function () use ($app, $content, $to, $message) {
            $message->reply()->create(
                [
                    'content' => $content
                ]
            );
            $app->customer_service->message(new Text($content))->to($to)->send();
        });
        $app->server->serve();
        return Helper::success("", "已回复" . $to);

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
            $Message = Message::query()->findOrFail($id);
            $Message->delete();
            return redirect()->to(route('admin.Message.index'))->with('message', '删除成功');
        } catch (\Exception $exception) {
            return redirect()->to(route('admin.Message.index'))->with('message', '删除失败');
        }
    }
}
