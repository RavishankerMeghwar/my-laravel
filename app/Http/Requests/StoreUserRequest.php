<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class StoreUserRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id|integer',
            'first_name'      => 'required|max:255',
            'last_name'       => 'required|max:255',
            'email'           => 'required|max:255|email|unique:users,email',
            'telephone'       => 'required|digits_between:4,20|numeric',
            'mobile_phone'    => 'nullable|digits_between:4,20|numeric',
            'password'        => 'required|min:6|max:15',
            'language'        => ['required', Rule::in(
                User::LANGUAGE_DUTCH,
                User::LANGUAGE_ENGLISH
            )]
        ];
    }
}
