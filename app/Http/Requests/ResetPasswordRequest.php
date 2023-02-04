<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends ApiRequest
{
  
    public function rules()
    {
        return [
            'reset_token' => 'required|integer|exists:users,reset_token',
            'password'    => 'required|min:6|max:15'
        ];
    }
}
