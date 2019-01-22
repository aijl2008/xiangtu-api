<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "id" => "required|unique:users",
            "name" => "required",
           // "avatar" => "url"
        ];
    }

    public function messages()
    {
        return [
            "id.unique" => "用户{$this->input('id')}已经存在了",
           // "avatar.url" => "用户头像的格式无效"
        ];
    }

    public function all($keys = NULL)
    {
        $data = [];
        foreach ([
                     "id" => "string",
                     "name" => "string",
                     "mobile" => "string",
                     "email" => "string",
                     "avatar" => "string",
                 ] as $field => $type) {
            $value = $this->input($field);
            if (is_null($value)) {
                continue;
            }
            $data[$field] = call_user_func(function ($value, $type) {
                switch ($type) {
                    case "int":
                        return (int)$value;
                        break;
                    case "string":
                        return (string)$value;
                        break;
                    case "boolean":
                        return (boolean)$value;
                        break;
                    default:
                        return $value;
                        break;
                }
            }, $value, $type);
        }
        $data["password"] = "不支持密码登录";
        return $data;
    }
}
