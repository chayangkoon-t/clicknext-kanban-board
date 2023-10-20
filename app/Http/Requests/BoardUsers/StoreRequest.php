<?php

namespace App\Http\Requests\BoardUsers;

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
            'board_id' => ['required'],
            'user_id' => ['required'],
        ];
    }
}
