<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/15
 * Time: 下午10:39
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "nickname" => "required"
        ];
    }

    public function messages()
    {
        return [
            "avatar.required" => "用户头像必须提供"
        ];
    }

    public function data()
    {
        Log::error(__METHOD__, [
            $_FILES, $_POST
        ]);
        return [
            "nickname" => (string)$this->input("nickname"),
            "mobile" => (string)$this->input("mobile"),
            "avatar" => call_user_func(function ($request) {
                if ($request->hasFile('avatar')) {
                    $filename = $this->avatar->store('avatar/' . date('Ym') . '/', 'public');
                } else {
                    return $request->input("avatar");
                }
                return url('/upload/' . $filename);
            }, $this)
        ];
    }
}