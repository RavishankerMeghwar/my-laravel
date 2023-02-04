<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordEmail extends ApiRequest
{
  
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
    }
}
