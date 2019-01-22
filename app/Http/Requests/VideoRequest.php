<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
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
        $video = $this->route("video");
        if ($video) {
            return [
                "title:required",
                "url" => "required|unique:videos,url," . $this->route("video")->id
            ];
        } else {
            return [
                "title:required",
                "url" => "required|unique:videos"
            ];
        }

    }


    public function messages()
    {
        return [
            "title.required" => "视频名称必须提供",
            "url.required" => "视频地址必须提供",
            "url.unique" => "该视频已经存在了"
        ];
    }

    public function data()
    {
        $data = [
            "title" => (string)$this->input("title","未命名"),
            "cover_url" => (string)$this->input("cover_url"),
            "file_id" => (string)$this->input("file_id"),
            "url" => (string)$this->input("url"),
            "visibility" => (int)$this->input("visibility"),
            "classification_id" => (int)$this->input("classification_id"),
            "uploaded_at" =>  date('Y-m-d H:i:s')
        ];
        return $data;
    }
}
