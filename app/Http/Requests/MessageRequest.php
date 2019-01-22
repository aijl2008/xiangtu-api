<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            "message:required"
        ];
    }


    public function messages()
    {
        return [
            "name.required" => "消息不能为空"
        ];
    }

    public function data()
    {
        return [
            "name" => $this->input('name'),
            "icon" => $this->input('icon'),
            "status" => (int)$this->input('status')
        ];
    }
}
