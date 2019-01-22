<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
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
            "name:required",
            "original_id:required",
            "poster:required",
            "keywords:required",
            "tip:required"
        ];
    }

    public function data()
    {
        return [
            "name" => (string)$this->input('name'),
            "original_id" => (string)$this->input('original_id'),
            "poster" => (string)$this->input('poster'),
            "keywords" => (string)$this->input('keywords'),
            "tip" => (string)$this->input('tip')
        ];
    }
}
