<?php

namespace App\Http\Requests;


class UpdatePasswordRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'password'    => 'required|min:6|max:15'
        ];
    }
}
