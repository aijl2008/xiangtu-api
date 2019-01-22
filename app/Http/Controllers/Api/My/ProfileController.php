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
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    function index(Request $request)
    {
        return Helper::success(
            [
                'user' => $request->user('api')
            ]
        );
    }

    function update(ProfileRequest $request)
    {
        $user = $request->user('api');
        $row = $request->data();
        if (empty($row)) {
            return Helper::error(-1, "无更新");
        }
        $user->update($row);
        return Helper::success(
            $user
        );
    }
}