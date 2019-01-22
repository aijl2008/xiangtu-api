<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformRequest extends FormRequest
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
            'content' => "required",
            'video_id' => "required"
        ];
    }

    public function data()
    {
        $data = $this->only(
            ['video_id', 'content']
        );
        $user = $this->user('api');
        $data['wechat_id'] = $user->id ?? 0;
        $data['ips'] = json_encode($this->ips());
        return $data;
    }
}
