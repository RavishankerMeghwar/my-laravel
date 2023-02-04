<?php

namespace App\Http\Requests;


class LoginRequest extends ApiRequest
{
  
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:6|max:15'
        ];
    }
}
