<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\User;
use Illuminate\Validation\Rule;

class StoreProject extends ApiRequest
{
    public function rules()
    {
        return [
            'organization_id'     => 'required|exists:organizations,id|integer',
            'project_template_id' => 'required|integer|exists:project_templates,id',
            'gender_type' => ['bail', 'required', Rule::in([
                Project::GENDER_TYPE_MR,
                Project::GENDER_TYPE_WOMEN,
                Project::GENDER_TYPE_NOT_SPECIFIED
            ])],
            'customer_type' => ['bail', 'required', Rule::in([
                Project::CUSTOMER_TYPE_COMPANY,
                Project::CUSTOMER_TYPE_PRIVATE
            ])],
            'phone_type' => ['bail', 'nullable', Rule::in([
                Project::PHONE_TYPE_PRIVATE,
                Project::PHONE_TYPE_MOBILE,
                Project::PHONE_TYPE_STORE
            ])],
            'language' => ['bail', 'nullable', Rule::in([
                User::LANGUAGE_DUTCH,
                user::LANGUAGE_ENGLISH
            ])],
            'title'                => 'nullable|string|max:255',
            'first_name'           => 'bail|required|string|max:255',
            'surname'              => 'bail|required|string|max:255',
            'email'                => 'nullable|email|max:255',
            'telephone'            => 'nullable|numeric',
            'address'              => 'required|string|max:255',
            'reference'            => 'nullable|string|max:255',
            'commissioning_date'   => 'nullable|date_format:d/m/Y',
            'tax_saving'           => 'nullable|numeric',
            'vat'                  => 'nullable|numeric',
            'extended_calculation' => 'nullable|boolean',
            'annual_consumption'   => 'nullable|numeric'
        ];
    }
}
