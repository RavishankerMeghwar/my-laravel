<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class StoreUserBySuperAdmin extends ApiRequest
{
    public function rules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id|integer',
            'first_name'      => 'required|max:255',
            'last_name'       => 'nullable|max:255',
            'email'           => 'required|max:255|unique:users,email|email',
            'telephone'       => 'nullable|digits_between:10,20|numeric',
            'mobile_phone'    => 'required|digits_between:10,20|numeric',
            'password'        => 'required|min:6|max:15',
            'language'        => ['required', Rule::in(
                User::LANGUAGE_DUTCH,
                User::LANGUAGE_ENGLISH
            )],
            'role'            => ['required', Rule::in(
                User::ROLE_MANAGER,
                User::ROLE_EMPLOYEE,
                User::ROLE_STOCK_MANAGER,
                User::ROLE_PLUMBER,
            )]
        ];
    }
}
