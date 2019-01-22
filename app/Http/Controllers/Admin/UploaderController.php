<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/13
 * Time: 下午6:51
 */

namespace App\Http\Controllers\Admin;


use App\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploaderController extends Controller
{


    public function upload(Request $request)
    {
        $file = $request->file('poster_file');
        if (!$file) {
            return Helper::error(-1, "请选择图片" . var_export($_POST, true));
        }
        $path = $file->store('upload');
        return Helper::success(
            [
                "url" => asset(Storage::url($path)),
                "path" => $path
            ]
        );
    }

}