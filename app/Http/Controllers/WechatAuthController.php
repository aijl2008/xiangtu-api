<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Wechat;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\Socialite\AuthorizeFailedException;

class WechatAuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login')->with('rows', Wechat::query()->has('video')->inRandomOrder()->take(12)->get());
    }

    public function redirect()
    {
        return $this->oauth()->redirect();
    }

    /**
     * @return \Overtrue\Socialite\Providers\WeChatProvider
     */
    private function oauth()
    {
        $config = [
            'app_id' => config("wechat.open_platform.default.app_id"),
            'secret' => config("wechat.open_platform.default.secret"),
            'oauth' => [
                'scopes' => config("wechat.open_platform.default.oauth.scopes"),
                'callback' => config("wechat.open_platform.default.oauth.callback") . urlencode(route('wechat.login.do'))
            ]
        ];
        $app = Factory::officialAccount($config);
        return $app->oauth;
    }

    public function mock(Request $request, $id)
    {
        $wechat = Wechat::query()->findOrFail($id);
        Auth::guard('wechat')->login($wechat);
        (new Log())->setRequest($request)->log("登录", $wechat->id, 0, 0, '模拟');
        return redirect()->to(route("my.videos.index"));
    }

    public function callback(Request $request)
    {
        try {
            $wechat = $this->oauth()->user()->getOriginal();
            if (!array_key_exists('unionid', $wechat)) {
                abort(403, '微信接口返回的值中找不到unionid');
            }
            $user = Wechat::query()->where('union_id', $wechat['unionid'])->first();
            if (!$user) {
                abort(403, "您必须首先使用小程序登录过后，才能使用扫描二维码");
//                $user = new Wechat();
//                $user->union_id = $wechat['unionid'];
//                $user->nickname = $wechat['nickname'];
//                $user->sex = $wechat['sex'];
//                $user->province = $wechat['province'];
//                $user->city = $wechat['city'];
//                $user->country = $wechat['country'];
//                $user->avatar = $wechat['headimgurl'];
//                $user->save();
//                (new Log())->log("注册", $wechat->id, 0, 0, '扫描二维码');
            }
            Auth::guard('wechat')->login($user);
            (new Log())->setRequest($request)->log("登录", $user->id, 0, 0, '扫描二维码');
            return redirect()->intended(route("my.videos.index"));
        } catch (AuthorizeFailedException $e) {
            abort(403, "认证已过期");
        }
    }

    public function logout(Request $request)
    {
        (new Log())->setRequest($request)->log("退出", $request->user('wechat')->id);
        Auth::guard('wechat')->logout();
        $request->session()->invalidate();
        return redirect()->to(route('wechat.login.show'));
    }
}