<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketUser extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'category_id' => 'required',
            'media_id' => 'required',
            'state_id' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];
    }
}
