<?php

namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['required', 'string'],
        ];
    }
}
