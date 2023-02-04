<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;

class StoreTranslationRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'key'     => 'required|max:255|unique:translations',
            'english' => 'required|max:60000',
            'german'  => 'nullable|max:60000',
            'other'   => 'nullable|max:60000',
            'role'    => ['required', Rule::in(
                User::ROLE_MANAGER,
                User::ROLE_EMPLOYEE,
                User::ROLE_SUPER_ADMIN,
                User::ROLE_STOCK_MANAGER
            )]
        ];
    }
}
