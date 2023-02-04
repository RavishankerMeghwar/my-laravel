<?php

namespace App\Http\Requests;


class VerifyEmailRequest extends ApiRequest
{
   
    public function rules()
    {
        return [
            'verification_code' => 'required|exists:users,verification_code',
            'email'             => 'required|exists:users,email'
        ];
    }
}
