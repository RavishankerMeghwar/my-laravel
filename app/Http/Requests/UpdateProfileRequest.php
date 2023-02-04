<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'first_name'       => 'required|max:255',
            'last_name'        => 'nullable|max:255',
            'telephone'        => 'nullable|digits_between:10,20|numeric',
            'mobile_phone'     => 'nullable|digits_between:10,20|numeric',
            'password'         => 'nullable|min:6',
            'confirm_password' => 'nullable|min:6|same:password',
            'language'        => ['required', Rule::in(
                User::LANGUAGE_DUTCH,
                User::LANGUAGE_ENGLISH
            )]
        ];
    }
}
